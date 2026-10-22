<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Faculty;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Programme;
use App\Support\EntryRequirements;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ImportWordpress extends Command
{
    protected $signature = 'wp:import {xml : Path to the WordPress export (WXR) file} {--no-media : Skip downloading images and videos}';

    protected $description = 'Import programmes, events, news and gallery albums from a WordPress export';

    /** @var array<int, string> attachment id => url */
    private array $attachments = [];

    /** Faculty slug => [name, category slugs that identify it] */
    private const FACULTIES = [
        'science-technology' => ['Faculty of Science & Technology', ['science-technology', 'foict']],
        'engineering' => ['Faculty of Engineering', ['engineering', 'foeng']],
        'commerce-management' => ['Faculty of Commerce & Management', ['commerce-management', 'fobm']],
        'arts-culture' => ['Faculty of Arts & Culture', ['arts-culture', 'fac', 'fohss']],
        'language-vocational-studies' => ['Faculty of Language & Vocational Studies', ['language-vocational-studies', 'flvs']],
    ];

    public function handle(): int
    {
        $path = $this->argument('xml');
        if (! is_file($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        $xml = simplexml_load_file($path, SimpleXMLElement::class, LIBXML_NOCDATA);
        $items = [];
        foreach ($xml->channel->item as $item) {
            $wp = $item->children('wp', true);
            $items[] = ['item' => $item, 'wp' => $wp, 'type' => (string) $wp->post_type, 'status' => (string) $wp->status];
            if ((string) $wp->post_type === 'attachment') {
                $this->attachments[(int) $wp->post_id] = (string) $wp->attachment_url;
            }
        }
        $this->info(count($this->attachments).' attachments indexed.');

        $faculties = $this->importFaculties();
        $this->importProgrammes($items, $faculties);
        $this->importEvents($items);
        $this->importPosts($items);
        $this->importGalleries($items);

        $this->info('Done.');

        return self::SUCCESS;
    }

    private function importFaculties(): array
    {
        $out = [];
        $order = 0;
        foreach (self::FACULTIES as $slug => [$name]) {
            $out[$slug] = Faculty::updateOrCreate(['slug' => $slug], ['name' => $name, 'sort_order' => ++$order]);
        }

        return $out;
    }

    private function importProgrammes(array $items, array $faculties): void
    {
        $n = 0;
        foreach ($items as $row) {
            if ($row['type'] !== 'lp_course' || $row['status'] !== 'publish') {
                continue;
            }
            $item = $row['item'];
            $wp = $row['wp'];
            $meta = $this->meta($wp);
            $cats = $this->terms($item, 'course_category');
            $title = trim((string) $item->title);
            $html = (string) $item->children('content', true)->encoded;

            preg_match('/href="([^"]+\.pdf)"/i', $html, $m);
            $brochure = isset($m[1]) ? $this->download($m[1], 'brochures') : null;

            $requirements = isset($meta['_lp_requirements']) ? array_values((array) @unserialize($meta['_lp_requirements'], ['allowed_classes' => false])) : [];

            Programme::updateOrCreate(['wp_id' => (int) $wp->post_id], [
                'faculty_id' => $this->facultyFor($cats, $faculties)?->id,
                'title' => $title,
                'slug' => $this->uniqueSlug(Programme::class, (string) $wp->post_name ?: $title, (int) $wp->post_id),
                'level' => $this->levelFor($title, $cats),
                'duration' => $this->duration($meta['_lp_duration'] ?? null),
                'requirements' => EntryRequirements::for($this->levelFor($title, $cats), array_values(array_filter($requirements)) ?: null),
                'brochure' => $brochure,
                'image' => isset($meta['_thumbnail_id']) ? $this->download($this->attachments[(int) $meta['_thumbnail_id']] ?? null, 'programmes') : null,
                'is_featured' => ($meta['_lp_featured'] ?? 'no') === 'yes',
            ]);
            $n++;
        }
        $this->info("Programmes: {$n}");
    }

    private function importEvents(array $items): void
    {
        $n = 0;
        foreach ($items as $row) {
            if ($row['type'] !== 'tribe_events' || $row['status'] !== 'publish') {
                continue;
            }
            $item = $row['item'];
            $wp = $row['wp'];
            $meta = $this->meta($wp);
            $title = trim((string) $item->title);
            $content = $this->clean((string) $item->children('content', true)->encoded);

            Event::updateOrCreate(['wp_id' => (int) $wp->post_id], [
                'title' => $title,
                'slug' => $this->uniqueSlug(Event::class, (string) $wp->post_name ?: $title, (int) $wp->post_id),
                'starts_on' => isset($meta['_EventStartDate']) ? substr($meta['_EventStartDate'], 0, 10) : null,
                'ends_on' => isset($meta['_EventEndDate']) ? substr($meta['_EventEndDate'], 0, 10) : null,
                'excerpt' => Str::limit(trim(strip_tags($content)), 180),
                'content' => $content,
                'image' => isset($meta['_thumbnail_id']) ? $this->download($this->attachments[(int) $meta['_thumbnail_id']] ?? null, 'events') : null,
            ]);
            $n++;
        }
        $this->info("Events: {$n}");
    }

    private function importPosts(array $items): void
    {
        $n = 0;
        foreach ($items as $row) {
            if ($row['type'] !== 'post' || $row['status'] !== 'publish') {
                continue;
            }
            $item = $row['item'];
            $wp = $row['wp'];
            $content = $this->clean((string) $item->children('content', true)->encoded);
            if (trim(strip_tags($content)) === '') {
                continue; // e.g. empty "Elementor #1095" placeholder
            }
            $meta = $this->meta($wp);
            $title = trim((string) $item->title);

            Post::updateOrCreate(['slug' => $this->uniqueSlug(Post::class, (string) $wp->post_name ?: $title, (int) $wp->post_id)], [
                'title' => $title,
                'excerpt' => Str::limit(trim(strip_tags($content)), 180),
                'content' => $content,
                'image' => isset($meta['_thumbnail_id']) ? $this->download($this->attachments[(int) $meta['_thumbnail_id']] ?? null, 'news') : null,
                'published_at' => (string) $wp->post_date,
            ]);
            $n++;
        }
        $this->info("News posts: {$n}");
    }

    private function importGalleries(array $items): void
    {
        $order = 0;
        $n = 0;
        foreach ($items as $row) {
            if ($row['type'] !== 'foogallery' || $row['status'] !== 'publish') {
                continue;
            }
            $meta = $this->meta($row['wp']);
            $ids = array_map('intval', (array) @unserialize($meta['foogallery_attachments'] ?? 'a:0:{}', ['allowed_classes' => false]));
            if (! $ids) {
                continue;
            }
            $title = trim((string) $row['item']->title);
            $album = GalleryAlbum::firstOrCreate(
                ['slug' => $this->uniqueSlug(GalleryAlbum::class, $title, (int) $row['wp']->post_id)],
                ['title' => $title, 'sort_order' => ++$order]
            );
            foreach ($ids as $i => $id) {
                $url = $this->attachments[$id] ?? null;
                if (! $url) {
                    continue;
                }
                $isVideo = (bool) preg_match('/\.(mp4|webm)$/i', $url);
                $file = $this->download($url, 'gallery');
                if (! $file) {
                    continue;
                }
                GalleryItem::firstOrCreate(
                    ['gallery_album_id' => $album->id, 'path' => $file],
                    ['type' => $isVideo ? 'video' : 'image', 'sort_order' => $i]
                );
            }
            $album->update(['cover_image' => $album->items()->where('type', 'image')->value('path')]);
            $n++;
        }
        $this->info("Gallery albums: {$n}");
    }

    private function meta($wp): array
    {
        $out = [];
        foreach ($wp->postmeta as $m) {
            $out[(string) $m->meta_key] = (string) $m->meta_value;
        }

        return $out;
    }

    /** @return string[] term nicenames for a taxonomy */
    private function terms(SimpleXMLElement $item, string $domain): array
    {
        $out = [];
        foreach ($item->category as $c) {
            if ((string) $c['domain'] === $domain) {
                $out[] = (string) $c['nicename'];
            }
        }

        return $out;
    }

    private function facultyFor(array $cats, array $faculties): ?Faculty
    {
        foreach (self::FACULTIES as $slug => [, $keys]) {
            foreach ($cats as $cat) {
                foreach ($keys as $key) {
                    if ($cat === $key || str_starts_with($cat, $key.'-')) {
                        return $faculties[$slug];
                    }
                }
            }
        }

        return null;
    }

    private function levelFor(string $title, array $cats): string
    {
        $t = strtolower($title);
        $catStr = implode(' ', $cats);

        return match (true) {
            str_contains($t, 'top-up') || str_contains($t, 'top up') => 'Top-up',
            (bool) preg_match('/\b(msc|mba|master|ma)\b/', $t) => 'Masters',
            (bool) preg_match('/\b(pgd|pgde|postgraduate|post graduate)\b/', $t) => 'Postgraduate Diploma',
            str_starts_with($t, 'hnd') || str_contains($t, 'higher national') => 'HND',
            (bool) preg_match('/\b(bsc|bachelor|ba|beng|degree|llb)\b/', $t) => 'Degree',
            str_starts_with($t, 'diploma') || str_contains($t, 'diploma') => 'Diploma',
            str_contains($catStr, 'master') => 'Masters',
            str_contains($catStr, 'pgde') || str_contains($catStr, 'post-graduate') => 'Postgraduate Diploma',
            str_contains($catStr, 'hnd') => 'HND',
            str_contains($catStr, 'degree') || str_contains($catStr, 'undergraduate') => 'Degree',
            str_contains($catStr, 'diploma') => 'Diploma',
            default => 'Other',
        };
    }

    private function duration(?string $raw): ?string
    {
        $raw = trim((string) $raw);

        return $raw === '' ? null : Str::of($raw)->replaceMatches('/\bweek\b/i', 'weeks')->replaceMatches('/\bmonth\b/i', 'months')->replaceMatches('/\byear\b/i', 'years')->toString();
    }

    /** Remove Elementor/WordPress block comments and inline styles; wrap loose text in paragraphs. */
    private function clean(string $html): string
    {
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        $html = preg_replace('/\sstyle="[^"]*"/i', '', $html);
        $html = preg_replace('/\sclass="[^"]*"/i', '', $html);
        $html = trim($html);
        if ($html !== '' && ! str_contains($html, '<p')) {
            $html = '<p>'.implode('</p><p>', preg_split('/\R{2,}/', $html)).'</p>';
        }

        return $html;
    }

    private function uniqueSlug(string $model, string $base, int $wpId): string
    {
        $slug = Str::slug(urldecode($base)) ?: 'item';

        // Models without a wp_id column (posts, albums) are keyed by this slug, so always make it stable.
        if (in_array($model, [GalleryAlbum::class], true)) {
            return "{$slug}-{$wpId}";
        }
        if ($model === Post::class) {
            return $slug;
        }

        return $model::where('slug', $slug)->where('wp_id', '!=', $wpId)->exists() ? "{$slug}-{$wpId}" : $slug;
    }

    /** Download a remote file into the public disk and return its relative path. */
    private function download(?string $url, string $folder): ?string
    {
        if (! $url || $this->option('no-media')) {
            return null;
        }
        $name = urldecode(basename(parse_url($url, PHP_URL_PATH)));
        $rel = "{$folder}/{$name}";
        if (Storage::disk('public')->exists($rel)) {
            return $rel;
        }
        try {
            $res = Http::timeout(120)->withOptions(['verify' => false])->get($url);
            if (! $res->successful()) {
                $this->warn("  skip ({$res->status()}): {$url}");

                return null;
            }
            Storage::disk('public')->put($rel, $res->body());
            $this->line("  + {$rel}");

            return $rel;
        } catch (\Throwable $e) {
            $this->warn("  failed: {$url} ({$e->getMessage()})");

            return null;
        }
    }
}
