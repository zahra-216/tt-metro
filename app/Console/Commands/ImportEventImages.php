<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class ImportEventImages extends Command
{
    protected $signature = 'wp:import-event-images {xml : Path to the WordPress export (WXR) file}';

    protected $description = 'Add the extra photos and videos attached to each WordPress event to its photo gallery';

    public function handle(): int
    {
        $xml = simplexml_load_file($this->argument('xml'), SimpleXMLElement::class, LIBXML_NOCDATA);
        $attachments = [];
        foreach ($xml->channel->item as $item) {
            $wp = $item->children('wp', true);
            if ((string) $wp->post_type === 'attachment') {
                $attachments[] = ['id' => (int) $wp->post_id, 'parent' => (int) $wp->post_parent, 'url' => (string) $wp->attachment_url];
            }
        }

        $added = 0;
        foreach (Event::whereNotNull('wp_id')->get() as $event) {
            $own = array_filter($attachments, fn ($a) => $a['parent'] === $event->wp_id && preg_match('/\.(jpe?g|png|webp|mp4)$/i', $a['url']));
            foreach (array_values($own) as $i => $a) {
                $file = $this->download($a['url'], 'events');
                if (! $file || $file === $event->image) {
                    continue;
                }
                $isVideo = (bool) preg_match('/\.mp4$/i', $file);
                $created = EventImage::firstOrCreate(
                    ['event_id' => $event->id, 'path' => $file],
                    ['type' => $isVideo ? 'video' : 'image', 'sort_order' => $i]
                );
                $added += $created->wasRecentlyCreated ? 1 : 0;
            }
        }
        $this->info("Event gallery items added: {$added}");

        return self::SUCCESS;
    }

    private function download(string $url, string $folder): ?string
    {
        $rel = $folder.'/'.urldecode(basename(parse_url($url, PHP_URL_PATH)));
        if (Storage::disk('public')->exists($rel)) {
            return $rel;
        }
        try {
            $res = Http::timeout(120)->get($url);
            if (! $res->successful()) {
                return null;
            }
            Storage::disk('public')->put($rel, $res->body());

            return $rel;
        } catch (\Throwable) {
            return null;
        }
    }
}