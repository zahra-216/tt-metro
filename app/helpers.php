<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $value = Setting::get($key, $default);

        return filled($value) ? $value : $default;
    }
}

if (! function_exists('whatsapp_url')) {
    /**
     * Link that opens a WhatsApp chat with the campus number (admin "Site settings", else the default below).
     * Accepts "+94 77 227 0348", "094772270348" or "0772270348". Null if the number looks unusable.
     */
    function whatsapp_url(?string $message = null): ?string
    {
        $digits = preg_replace('/\D/', '', (string) setting('whatsapp_number', '+94 77 227 0348'));

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);      // 0094... -> 94...
        } elseif (str_starts_with($digits, '0')) {
            $digits = '94'.substr($digits, 1); // 077... -> 9477...
        }

        if (strlen($digits) < 9 || strlen($digits) > 15) {
            return null;
        }

        return 'https://wa.me/'.$digits.($message ? '?text='.rawurlencode($message) : '');
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