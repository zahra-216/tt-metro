<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $value = Setting::get($key, $default);

        return filled($value) ? $value : $default;
    }
}

if (! function_exists('keep_name')) {
    /** Escapes a heading and stops "TT Metro Campus" from being split across two lines. */
    function keep_name(?string $text): Illuminate\Support\HtmlString
    {
        return new Illuminate\Support\HtmlString(
            str_replace('TT Metro Campus', '<span class="whitespace-nowrap">TT Metro Campus</span>', e($text))
        );
    }
}

if (! function_exists('media')) {
    /** Public URL for a file stored on the public disk, or null. */
    function media(?string $path): ?string
    {
        return $path ? asset('storage/'.ltrim($path, '/')) : null;
    }
}