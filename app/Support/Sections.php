<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Website sections the admin can switch on or off ("Show / hide sections" in the admin).
 * Hiding never deletes anything: the text, pictures and lists stay saved and come back when switched on.
 * The choice is stored as a setting named "section_<key>" ("1" = shown, "0" = hidden). A section with no saved
 * choice uses its default below, so adding a section here never changes the live site by itself.
 */
class Sections
{
    /**
     * page title => [route name to preview (or null), [key => [label, what it is, shown by default]]]
     *
     * @return array<string, array{route: ?string, items: array<string, array{0: string, 1: string, 2: bool}>}>
     */
    public static function all(): array
    {
        return [
            'Whole pages (hide a page completely)' => ['route' => null, 'items' => [
                'page.about' => ['About Us page', 'Hides the page, its menu links and the buttons that lead to it.', true],
                'page.student_life' => ['Student Life page', 'Hides the page and its menu links.', true],
                'page.gallery' => ['Gallery page', 'Hides the gallery and every album, and the menu links.', true],
                'page.events' => ['Events and news pages', 'Hides the events list, every event and news article, the menu links and the events on the home page.', true],
            ]],
            'Inside the Home page' => ['route' => 'home', 'items' => [
                'home.stats' => ['Numbers strip', 'The row of big numbers (students, programmes...) under the top banner.', true],
                'home.welcome' => ['Welcome and why choose us', 'The welcome text with the four boxes: Quality Education, Expert Teachers...', true],
                'home.faculties' => ['Faculties', 'The faculty cards: "Find the right path for your career".', true],
                'home.programmes' => ['Popular programmes', 'The programme cards: "Programmes students love".', true],
                'home.events' => ['Events and news', 'The latest events.', true],
                'home.board' => ['Board of Directors', 'The photos of the board members.', true],
                'home.testimonials' => ['What our students say', 'The student testimonials.', true],
                'home.contact' => ['Find us', 'The blue contact box, the map and the branches list.', true],
            ]],
            'Inside the About Us page' => ['route' => 'about', 'items' => [
                'about.story' => ['Our story and numbers', 'The welcome text with the numbers beside it.', true],
                'about.chairman' => ['Message from the Founder Chairman', 'The chairman message and photo.', true],
                'about.vision' => ['Vision, Mission and Motto', 'The three boxes.', true],
                'about.what_we_do' => ['What we do', 'Text box. Edit the words in Site settings > About page.', true],
                'about.program_options' => ['Programme options', 'Text box. Edit the words in Site settings > About page.', true],
                'about.registrations' => ['Registrations and partnerships (text)', 'Text box. Edit the words in Site settings > About page.', true],
                'about.satellite' => ['Satellite centre opportunity', 'Text box. Edit the words in Site settings > About page.', true],
                'about.values' => ['Our values', 'Text box. Edit the words in Site settings > About page.', true],
                'about.team' => ['Our people (team)', 'The tabs: Board, Council, Senate, Staff and Lecturers.', true],
                'about.partners' => ['Partners and affiliations (logos)', 'The grid of partner logos. Edit them under People > Partners.', false],
            ]],
            'Inside the Student Life page' => ['route' => 'student-life', 'items' => [
                'student_life.facilities' => ['Campus facilities', 'The facilities cards.', true],
                'student_life.scholarships' => ['Scholarships', 'The scholarship list.', true],
                'student_life.services' => ['Student services', 'The services cards.', true],
            ]],
            'Inside the Contact page' => ['route' => 'contact', 'items' => [
                'contact.map' => ['Map', 'The Google map.', true],
                'contact.branches' => ['Branches', 'The list of other branches.', true],
            ]],
            'Inside every programme page' => ['route' => 'academics', 'items' => [
                'programme.modules' => ['Modules box', 'The semester tabs with the module lists.', true],
                'programme.advice' => ['Need advice? box', 'The enquiry form and call buttons.', true],
                'programme.related' => ['Related programmes', 'The cards at the bottom of a programme page.', true],
            ]],
        ];
    }

    /** Is this page switched on? Only About Us, Student Life, Gallery and Events can be switched off. */
    public static function pageOn(string $page): bool
    {
        return ! in_array($page, ['about', 'student_life', 'gallery', 'events'], true) || self::on('page.'.$page);
    }

    /** Every key with its default. */
    public static function defaults(): array
    {
        $out = [];
        foreach (self::all() as $page) {
            foreach ($page['items'] as $key => $item) {
                $out[$key] = $item[2];
            }
        }

        return $out;
    }

    public static function settingKey(string $key): string
    {
        return 'section_'.str_replace('.', '_', $key);
    }

    /** Is this section shown on the website? */
    public static function on(string $key): bool
    {
        $saved = Setting::get(self::settingKey($key));

        if ($saved === null || $saved === '') {
            return self::defaults()[$key] ?? true;
        }

        return (string) $saved === '1';
    }
}
