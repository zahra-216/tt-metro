<?php

namespace App\Support;

/** Small helpers for the photos and videos of events and gallery albums. */
class MediaFiles
{
    /** "video" for an uploaded video file, otherwise "image". */
    public static function typeFor(string $path): string
    {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['mp4', 'webm'], true) ? 'video' : 'image';
    }

    /**
     * A YouTube or Vimeo link in the one form the website can play, or null when it is neither.
     * Accepts youtu.be/ID, youtube.com/watch?v=ID (with extra parts), /shorts/ID, /live/ID, /embed/ID and vimeo.com/ID.
     */
    public static function videoLink(string $link): ?string
    {
        $link = trim($link);

        if (preg_match('~^(?:https?://)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com/(?:watch\?(?:[^#\s]*&)?v=|shorts/|live/|embed/))([A-Za-z0-9_-]{11})(?![A-Za-z0-9_-])~i', $link, $m)) {
            return 'https://www.youtube.com/watch?v='.$m[1];
        }

        if (preg_match('~^(?:https?://)?(?:www\.)?(?:vimeo\.com/|player\.vimeo\.com/video/)(\d{6,12})(?!\d)~i', $link, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return null;
    }

    /** The non-empty lines of a "one link per line" box. */
    public static function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $text)), fn ($l) => $l !== ''));
    }
}
