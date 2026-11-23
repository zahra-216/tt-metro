<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\Post;
use App\Models\Programme;

/**
 * sitemap.xml for Google (Search Console > Sitemaps). Built from the database every time it is opened, so new
 * programmes, events, news and albums appear by themselves. Pages switched off in the admin
 * (Site > Show / hide sections) and items that are switched off are left out.
 */
class SeoController extends Controller
{
    public function sitemap()
    {
        $urls = [['loc' => route('home')], ['loc' => route('academics')], ['loc' => route('contact')], ['loc' => route('apply')]];

        foreach (['about' => 'about', 'student_life' => 'student-life', 'gallery' => 'gallery', 'events' => 'events'] as $page => $route) {
            if (section_on('page.'.$page)) {
                $urls[] = ['loc' => route($route)];
            }
        }

        foreach (Programme::where('is_active', true)->orderBy('title')->get(['slug', 'updated_at']) as $p) {
            $urls[] = ['loc' => route('programme', $p->slug), 'lastmod' => $p->updated_at];
        }

        if (section_on('page.events')) {
            foreach (Event::where('is_active', true)->orderByDesc('starts_on')->get(['slug', 'updated_at']) as $e) {
                $urls[] = ['loc' => route('event', $e->slug), 'lastmod' => $e->updated_at];
            }
            foreach (Post::where('is_active', true)->orderByDesc('published_at')->get(['slug', 'updated_at']) as $p) {
                $urls[] = ['loc' => route('post', $p->slug), 'lastmod' => $p->updated_at];
            }
        }

        if (section_on('page.gallery')) {
            foreach (GalleryAlbum::where('is_active', true)->orderBy('sort_order')->get(['slug', 'updated_at']) as $a) {
                $urls[] = ['loc' => route('album', $a->slug), 'lastmod' => $a->updated_at];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>'.htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc>';
            if (! empty($u['lastmod'])) {
                $xml .= '<lastmod>'.$u['lastmod']->toAtomString().'</lastmod>';
            }
            $xml .= "</url>\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8', 'Cache-Control' => 'public, max-age=3600']);
    }
}
