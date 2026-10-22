<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\Setting;
use App\Models\StudentLifeItem;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class ImportWordpressPages extends Command
{
    protected $signature = 'wp:import-pages {xml : Path to the WordPress export (WXR) file} {--no-media : Skip downloading photos}';

    protected $description = 'Import people, About page text, facilities, scholarships and partners from the Elementor pages of a WordPress export';

    /** @var array<string, array> page title => Elementor element tree */
    private array $pages = [];

    /** @var array<string, string> lower-case filename without extension => attachment URL */
    private array $byStem = [];

    public function handle(): int
    {
        $path = $this->argument('xml');
        if (! is_file($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        $xml = simplexml_load_file($path, SimpleXMLElement::class, LIBXML_NOCDATA);
        foreach ($xml->channel->item as $item) {
            $wp = $item->children('wp', true);
            if ((string) $wp->post_type === 'attachment') {
                $url = (string) $wp->attachment_url;
                $this->byStem[strtolower(pathinfo($url, PATHINFO_FILENAME))] ??= $url;
            }
            if ((string) $wp->post_type !== 'page' || (string) $wp->status !== 'publish') {
                continue;
            }
            foreach ($wp->postmeta as $meta) {
                if ((string) $meta->meta_key === '_elementor_data') {
                    $this->pages[html_entity_decode(trim((string) $item->title))] = json_decode((string) $meta->meta_value, true) ?: [];
                }
            }
        }

        $this->people();
        $this->about();
        $this->facilities();
        $this->scholarships();
        $this->partners();
        $this->info('Done.');

        return self::SUCCESS;
    }

    private function people(): void
    {
        $this->intro('Governing Council', 'intro_council');
        $this->intro('Academic Senate', 'intro_senate');
        $this->intro('Academic & Management Staff', 'intro_staff');
        $this->intro('Our Lecturers', 'intro_lecturers');

        $council = $this->members('Governing Council');
        $this->saveMembers($council, 'council');
        $this->saveMembers($this->members('Academic Senate'), 'senate');

        // Board of Directors is the four people featured on the old home page.
        $board = array_values(array_filter($council, fn ($m) => preg_match('/Sarjoon|Rinza|Jahan|Imthiyas/i', $m['name'])));
        TeamMember::where('group', 'board')->whereNull('photo')->delete();
        $this->saveMembers($board, 'board');

        // The staff page lists staff first, then repeats the lecturers; the lecturer page repeats the deans first.
        $staffAll = $this->members('Academic & Management Staff');
        $cut = collect($staffAll)->search(fn ($m) => str_contains($m['name'], 'ZamZam'));
        $this->saveMembers($cut === false ? $staffAll : array_slice($staffAll, 0, $cut), 'staff');

        $lecturers = $this->members('Our Lecturers');
        $cut = collect($lecturers)->search(fn ($m) => str_contains($m['name'], 'ZamZam'));
        $this->saveMembers($cut === false ? $lecturers : array_slice($lecturers, $cut), 'lecturers');
    }

    private function about(): void
    {
        $tree = $this->pages['About Us'] ?? [];
        $pairs = [];
        $heading = null;
        foreach ($this->widgets($tree) as $w) {
            $s = $w['settings'] ?? [];
            switch ($w['widgetType'] ?? '') {
                case 'heading':
                    $heading = strtolower(trim(strip_tags($s['title'] ?? '')));
                    break;
                case 'text-editor':
                    if ($heading) {
                        $pairs[$heading] = $this->clean($s['editor'] ?? '');
                    }
                    break;
                case 'icon-box':
                    $pairs[strtolower($s['title_text'] ?? '')] = trim($s['description_text'] ?? '');
                    break;
                case 'image':
                    if (! isset($pairs['chairman_image']) && ! empty($s['image']['url'])) {
                        $pairs['chairman_image'] = $this->download($s['image']['url'], 'about');
                    }
                    break;
            }
        }

        $map = [
            'message from founder chairman' => 'chairman_message',
            'what we do?' => 'what_we_do',
            'program options' => 'program_options',
            'our registrations & partnerships' => 'registrations',
            'authorized partner to offer satellite centre opportunity' => 'satellite',
            'values' => 'values',
            'vision' => 'vision',
            'mission' => 'mission',
            'motto' => 'motto',
            'chairman_image' => 'chairman_image',
        ];
        foreach ($map as $from => $key) {
            $value = $pairs[$from] ?? $pairs[str_replace('&', '&amp;', $from)] ?? null;
            if (filled($value)) {
                Setting::put($key, $value);
            }
        }
        $this->info('About page text imported.');
    }

    private function facilities(): void
    {
        $this->intro('Facilities', 'intro_facilities');
        $items = [];
        $current = null;
        $first = true;
        foreach ($this->widgets($this->pages['Facilities'] ?? []) as $w) {
            $s = $w['settings'] ?? [];
            $type = $w['widgetType'] ?? '';
            if ($type === 'heading') {
                if ($first) {
                    $first = false;

                    continue; // page title
                }
                $current = trim(strip_tags($s['title'] ?? ''));
                $items[$current] = ['description' => '', 'image' => null];
            } elseif ($current && $type === 'text-editor') {
                $items[$current]['description'] .= $this->clean($s['editor'] ?? '');
            } elseif ($current && $type === 'image' && ! empty($s['image']['url'])) {
                $items[$current]['image'] = $this->download($s['image']['url'], 'student-life');
            }
        }

        $order = 0;
        foreach ($items as $title => $data) {
            StudentLifeItem::updateOrCreate(['category' => 'facility', 'title' => $title], $data + ['sort_order' => ++$order]);
        }
        $this->info('Facilities: '.count($items));
    }

    private function scholarships(): void
    {
        $this->intro('Scholarship', 'intro_scholarship');
        $order = 0;
        foreach ($this->widgets($this->pages['Scholarship'] ?? []) as $w) {
            if (($w['widgetType'] ?? '') !== 'nested-accordion') {
                continue;
            }
            foreach ($w['settings']['items'] ?? [] as $i => $row) {
                $container = $w['elements'][$i] ?? null;
                if (! $container) {
                    continue;
                }
                $html = '';
                foreach ($this->widgets([$container]) as $inner) {
                    $s = $inner['settings'] ?? [];
                    match ($inner['widgetType'] ?? '') {
                        'text-editor' => $html .= $this->clean($s['editor'] ?? ''),
                        'heading' => $html .= '<h3>'.e(trim(strip_tags($s['title'] ?? ''))).'</h3>',
                        'icon-box' => $html .= '<p><strong>'.e($s['title_text'] ?? '').'</strong> - '.e($s['description_text'] ?? '').'</p>',
                        default => null,
                    };
                }
                StudentLifeItem::updateOrCreate(
                    ['category' => 'scholarship', 'title' => trim($row['item_title'] ?? '')],
                    ['description' => $html, 'sort_order' => ++$order]
                );
            }
        }
        $this->info("Scholarships: {$order}");
    }

    private function partners(): void
    {
        $this->intro('Our Affiliation & Partners', 'intro_partners');
        $order = 0;
        foreach ($this->widgets($this->pages['Our Affiliation & Partners'] ?? []) as $w) {
            if (($w['widgetType'] ?? '') !== 'eael-flip-box') {
                continue;
            }
            $s = $w['settings'];
            $name = trim($s['eael_flipbox_back_title'] ?? '');
            if ($name === '') {
                continue;
            }
            $logo = $s['eael_flipbox_front_bg_color_image']['url'] ?? $s['eael_flipbox_back_bg_color_image']['url'] ?? null;
            Partner::updateOrCreate(['name' => ucwords(strtolower($name))], ['logo' => $this->download($logo, 'partners'), 'sort_order' => ++$order]);
        }
        $this->info("Partners: {$order}");
    }

    /** @return array<int, array{name: string, role: string, bio: ?string, photo: ?string}> */
    private function members(string $page): array
    {
        $out = [];
        foreach ($this->widgets($this->pages[$page] ?? []) as $w) {
            if (($w['widgetType'] ?? '') !== 'bdt-member') {
                continue;
            }
            $s = $w['settings'];
            $out[] = [
                'name' => $this->tidy($s['name'] ?? ''),
                'role' => $this->tidy($s['role'] ?? ''),
                'bio' => filled($s['description_text'] ?? null) ? $this->tidy($s['description_text']) : null,
                'photo' => $s['photo']['url'] ?? null,
            ];
        }

        return $out;
    }

    private function saveMembers(array $members, string $group): void
    {
        foreach ($members as $i => $m) {
            TeamMember::updateOrCreate(
                ['group' => $group, 'name' => $m['name']],
                ['position' => $m['role'], 'bio' => $m['bio'], 'photo' => $this->download($m['photo'], 'team'), 'sort_order' => $i, 'is_active' => true]
            );
        }
        $this->info(ucfirst($group).': '.count($members));
    }

    private function intro(string $page, string $key): void
    {
        foreach ($this->widgets($this->pages[$page] ?? []) as $w) {
            if (($w['widgetType'] ?? '') === 'text-editor') {
                Setting::put($key, $this->clean($w['settings']['editor'] ?? ''));

                return;
            }
        }
    }

    /** Flatten an Elementor tree into widgets in document order. */
    private function widgets(array $elements): \Generator
    {
        foreach ($elements as $el) {
            if (($el['elType'] ?? '') === 'widget') {
                yield $el;
            }
            if (($el['widgetType'] ?? '') === 'nested-accordion') {
                continue; // its containers are read by scholarships()
            }
            if (! empty($el['elements'])) {
                yield from $this->widgets($el['elements']);
            }
        }
    }

    private function tidy(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($text))));
    }

    private function clean(string $html): string
    {
        $html = html_entity_decode(preg_replace('/&lt;\/?pre\s*&gt;/i', '', $html), ENT_QUOTES | ENT_HTML5);
        $html = preg_replace('/\s(style|class|id)="[^"]*"/i', '', $html);
        $html = trim(strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><a><h2><h3><h4>'));

        return $html !== '' && ! str_contains($html, '<p') ? '<p>'.implode('</p><p>', preg_split('/\R{2,}|<br\s*\/?>\s*<br\s*\/?>/', $html)).'</p>' : $html;
    }

    private function download(?string $url, string $folder): ?string
    {
        if (! $url || $this->option('no-media')) {
            return null;
        }
        $rel = $folder.'/'.urldecode(basename(parse_url($url, PHP_URL_PATH)));
        if (Storage::disk('public')->exists($rel)) {
            return $rel;
        }
        try {
            $res = Http::timeout(120)->withOptions(['verify' => false])->get($url);
            if (! $res->successful()) {
                // The page may point at a file WordPress later re-uploaded under another extension.
                $alt = $this->byStem[strtolower(pathinfo($url, PATHINFO_FILENAME))] ?? null;
                if ($alt && $alt !== $url) {
                    return $this->download($alt, $folder);
                }
                $this->warn("  skip ({$res->status()}): {$url}");

                return null;
            }
            Storage::disk('public')->put($rel, $res->body());

            return $rel;
        } catch (\Throwable $e) {
            $this->warn("  failed: {$url} ({$e->getMessage()})");

            return null;
        }
    }
}
