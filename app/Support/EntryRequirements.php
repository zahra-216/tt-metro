<?php

namespace App\Support;

/**
 * Turns the loosely worded entry requirements of the old website into standard, professional wording.
 * Wording is chosen by programme level; a few programmes with distinct criteria keep them.
 */
class EntryRequirements
{
    private const MOTIVATION = 'Demonstrated motivation and commitment to complete the programme';

    /** @param  array<int, string>|null  $raw  requirements as written on the old site */
    public static function for(string $level, ?array $raw = null): array
    {
        $text = strtolower(implode(' ', $raw ?? []));

        if (str_contains($text, 'psychology and counselling')) {
            return [
                'Pass in the G.C.E. Ordinary Level (O/L) examination, or three passes in any stream at the G.C.E. Advanced Level (A/L)',
                'A recognised certificate in Psychology and Counselling or a related field',
            ];
        }
        if (str_contains($text, 'business management or a related field')) {
            return [
                'Pass in the G.C.E. Ordinary Level (O/L) examination, or three passes in any stream at the G.C.E. Advanced Level (A/L)',
                'A recognised certificate in Business Management or a related field',
                'Alternatively, English-medium education in the National Curriculum or the Edexcel / Cambridge curriculum',
            ];
        }
        if (str_contains($text, 'nvq')) {
            return [
                'G.C.E. Ordinary Level (O/L) candidates awaiting examination results',
                'Alternatively, a relevant qualification at NVQ Level 3',
                self::MOTIVATION,
            ];
        }
        if (str_contains($text, 'pending results')) {
            return [
                'G.C.E. Advanced Level (A/L) candidates awaiting examination results',
                'Alternatively, a completed Diploma in a relevant discipline',
                self::MOTIVATION,
            ];
        }

        return match ($level) {
            'Diploma' => [
                'Successful completion of the G.C.E. Ordinary Level (O/L) examination',
                'Relevant professional skills or work experience (desirable)',
                self::MOTIVATION,
            ],
            'HND' => [
                'Successful completion of the G.C.E. Ordinary Level (O/L) examination, or equivalent',
                'Alternatively, a completed Diploma in a relevant discipline',
                self::MOTIVATION,
            ],
            'Degree' => [
                "G.C.E. Advanced Level (A/L) examination with a minimum of three passes ('S' grades)",
                'Alternatively, a completed Higher National Diploma (HND) in a relevant discipline',
                self::MOTIVATION,
            ],
            'Top-up' => [
                'A completed Higher National Diploma (HND) or equivalent qualification in a relevant discipline',
                "Alternatively, G.C.E. Advanced Level (A/L) with a minimum of three passes ('S' grades)",
                self::MOTIVATION,
            ],
            'Postgraduate Diploma' => [
                "A recognised Bachelor's degree or equivalent qualification in a relevant discipline",
                'Proficiency in English, the language of instruction',
            ],
            'Masters' => [
                "A recognised Bachelor's degree in a relevant or related discipline",
                'Proficiency in English, the language of instruction',
            ],
            default => $raw ?? [],
        };
    }
}