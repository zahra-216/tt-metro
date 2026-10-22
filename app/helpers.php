<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $value = Setting::get($key, $default);

        return filled($value) ? $value : $default;
    }
}

if (! function_exists('media')) {
    /** Public URL for a file stored on the public disk, or null. */
    function media(?string $path): ?string
    {
        return $path ? asset('storage/'.ltrim($path, '/')) : null;
    }
}