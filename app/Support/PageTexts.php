<?php

namespace App\Support;

use App\Models\Faculty;
use App\Models\Setting;

/**
 * The names and headings of the website pages, editable in the admin (Site > Pages).
 * Until the admin changes a box (or after emptying it) the standard wording written here is used.
 * Page addresses never change.
 * Saved as settings named "page_<page>_<field>".
 */
class PageTexts
{
    /** Boxes the admin can fill, with the longest text each one accepts. */
    public const FIELDS = ['menu' => 20, 'heading' => 60, 'subtitle' => 200, 'description' => 300];

    private const NAME = ' - TT Metro Campus';

    /**
     * page => label in the admin, route, and the standard wording. "footer", "crumb" and "tab" are only used where
     * the standard wording differs from the menu name; once the admin renames the page, the new words are used.
     *
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return [
            'home' => ['label' => 'Home', 'route' => 'home', 'menu' => 'Home'],
            'about' => ['label' => 'About Us', 'route' => 'about', 'menu' => 'About Us', 'tab' => 'About Us',
                'heading' => 'About Us',
                'subtitle' => 'An innovative higher education institute established in 2015 in Sri Lanka.',
                'description' => 'Learn about TT Metro Campus in Mount Lavinia, Sri Lanka: our story, vision and mission, the Founder Chairman message and the team behind the campus.'],
            'academics' => ['label' => 'Academics', 'route' => 'academics', 'menu' => 'Academics', 'tab' => 'Academics',
                'heading' => 'Academics'],
            'student_life' => ['label' => 'Student Life', 'route' => 'student-life', 'menu' => 'Student Life', 'tab' => 'Student Life',
                'heading' => 'Student Life',
                'subtitle' => 'Facilities, scholarships and services that make campus life rewarding.',
                'description' => 'Campus facilities, scholarships and student services at TT Metro Campus, Mount Lavinia, Sri Lanka.'],
            'gallery' => ['label' => 'Gallery', 'route' => 'gallery', 'menu' => 'Gallery', 'tab' => 'Gallery',
                'heading' => 'Gallery',
                'subtitle' => 'Moments from our campus - ceremonies, workshops, excursions and more.',
                'description' => 'Photos from events, celebrations and campus life at TT Metro Campus, Sri Lanka.'],
            'events' => ['label' => 'Events and News', 'route' => 'events', 'menu' => 'Events', 'tab' => 'Events',
                'heading' => 'Events and News',
                'subtitle' => 'Celebrations, workshops, partnerships and milestones at TT Metro Campus.',
                'description' => 'Latest events, news and announcements from TT Metro Campus, Mount Lavinia, Sri Lanka.'],
            'contact' => ['label' => 'Contact', 'route' => 'contact', 'menu' => 'Contact', 'footer' => 'Contact Us', 'tab' => 'Contact Us',
                'heading' => 'Contact Us',
                'subtitle' => 'We would love to hear from you. Reach out with any questions about admissions or programmes.',
                'description' => 'Contact TT Metro Campus in Mount Lavinia, Sri Lanka: address, phone numbers, email, opening hours, map and branches.'],
            'apply' => ['label' => 'Apply Now', 'route' => 'apply', 'menu' => 'Apply Now', 'crumb' => 'Apply', 'tab' => 'Apply Now',
                'heading' => 'Apply Now',
                'subtitle' => 'Tell us a little about yourself and our admissions team will get in touch.'],
            'privacy' => ['label' => 'Privacy Policy', 'route' => 'privacy', 'menu' => 'Privacy Policy', 'tab' => 'Privacy Policy',
                'heading' => 'Privacy Policy',
                'subtitle' => 'How we handle the information you share with TT Metro Campus.',
                'description' => 'How TT Metro Campus collects, uses and protects the information you give us through our website, enquiry forms, online classes and learning portal.'],
        ];
    }

    /** Which boxes a page has in the admin. The home page words are in Site settings, so it only has a menu name. */
    public static function fields(string $page): array
    {
        return $page === 'home' ? ['menu'] : array_keys(self::FIELDS);
    }

    /** The Apply button sits in the narrow top bar of tablets too, so its name has to be shorter. */
    public static function maxLength(string $page, string $field): int
    {
        return $page === 'apply' && $field === 'menu' ? 14 : self::FIELDS[$field];
    }

    public static function key(string $page, string $field): string
    {
        return "page_{$page}_{$field}";
    }

    /** What the admin changed it to, or null while the standard wording is used. */
    public static function saved(string $page, string $field): ?string
    {
        $value = trim((string) Setting::get(self::key($page, $field)));

        return $value === '' ? null : $value;
    }

    /** Standard texts that name the offered levels or count the faculties, so they change on their own. */
    public static function followsProgrammes(string $page, string $field): bool
    {
        return ($page === 'academics' && in_array($field, ['subtitle', 'description'], true))
            || ($page === 'apply' && $field === 'description');
    }

    /** The standard wording, used until the admin changes it. */
    public static function standard(string $page, string $field): string
    {
        // these two follow the programmes and faculties currently on the website
        if ($page === 'academics' && $field === 'subtitle') {
            $count = Faculty::visible()->count();

            return levels_sentence(offered_levels()).' programmes across '.$count.' '.($count === 1 ? 'faculty' : 'faculties').'.';
        }

        if ($field === 'description' && in_array($page, ['academics', 'apply'], true)) {
            $levels = levels_sentence(offered_levels());

            return $page === 'academics'
                ? "Explore {$levels} programmes at TT Metro Campus, Mount Lavinia, Sri Lanka. See modules, entry requirements and how to apply."
                : "Apply online for {$levels} programmes at TT Metro Campus, Mount Lavinia, Sri Lanka.";
        }

        return self::all()[$page][$field] ?? '';
    }

    public static function get(string $page, string $field): string
    {
        return self::saved($page, $field) ?? self::standard($page, $field);
    }

    /** The page's name in a link: "menu" (top menu), "footer" (bottom links) or "crumb" (path above the heading). */
    public static function name(string $page, string $place = 'menu'): string
    {
        return self::saved($page, 'menu') ?? self::all()[$page][$place] ?? self::all()[$page]['menu'];
    }

    /** Title shown on the browser tab and in Google. */
    public static function title(string $page): string
    {
        return (self::saved($page, 'heading') ?? self::saved($page, 'menu') ?? self::all()[$page]['tab']).self::NAME;
    }
}
