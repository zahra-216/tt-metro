<?php

namespace App\Console\Commands;

use App\Models\Programme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Keeps only the programmes the campus offers now visible on the website. Everything else is switched to
 * inactive (not deleted), so it can be switched back on in the admin (Programmes, "Active" switch).
 *
 * php artisan programmes:set-active --dry-run    shows what would change, changes nothing
 * php artisan programmes:set-active              does it
 */
class SetActiveProgrammes extends Command
{
    protected $signature = 'programmes:set-active {--dry-run : Only show what would change}';

    protected $description = 'Keep only the programmes the campus offers now active; make all others inactive.';

    /** The campus list (level => the programme titles as they appear on the website). */
    private const OFFERED = [
        'Diploma' => [
            'Diploma in English',
            'Diploma in Information Technology',
            'Diploma in Digital Marketing',
            'Diploma in Artificial Intelligence',
            'Diploma in Cyber Security',
            'Diploma in UI & UX and Full Stack Development',
            'Diploma in Mobile Development',
        ],
        'HND' => [
            'HND in Business Management',
            'HND in Data Science',
            'HND in Artificial Intelligence',
            'HND in Information Technology',
            'HND in Cyber Security',
            'HND in Computer Science',
            'HND in English',
        ],
        'Degree' => [
            'Bachelor of Human Resources Management',
            'Bachelor of Business Management',
            'Bachelor of English Language & Linguistics',
            'Bachelor of Information Technology (BIT)',
            'BSc in Information Technology',
            'BSc in Cyber Security & Digital Forensics',
            'BSc in Data Science',
            'BSc in Artificial Intelligence',
            'BSc in Computer Science',
        ],
    ];

    public function handle(): int
    {
        $keep = [];
        $missing = [];

        foreach (self::OFFERED as $level => $titles) {
            foreach ($titles as $title) {
                $programme = Programme::where('level', $level)->whereRaw('LOWER(title) = ?', [mb_strtolower($title)])->first();

                if ($programme) {
                    $keep[$programme->id] = $programme;
                } else {
                    $missing[] = "{$level}: {$title}";
                }
            }
        }

        $all = Programme::orderBy('level')->orderBy('title')->get();
        $toShow = $all->filter(fn ($p) => isset($keep[$p->id]) && ! $p->is_active);
        $toHide = $all->filter(fn ($p) => ! isset($keep[$p->id]) && $p->is_active);

        $this->info('Programmes on the site: '.$all->count());
        $this->info('Will be ACTIVE (campus list): '.count($keep));
        $this->table(['Level', 'Title', 'Now'], collect($keep)->sortBy(fn ($p) => $p->level.$p->title)
            ->map(fn ($p) => [$p->level, $p->title, $p->is_active ? 'active' : 'inactive (will be switched on)'])->values()->all());

        $this->warn('Will be switched to INACTIVE: '.$toHide->count().' (already inactive: '.$all->where('is_active', false)->whereNotIn('id', array_keys($keep))->count().')');
        foreach ($toHide->groupBy('level') as $level => $group) {
            $this->line("  {$level}: ".$group->count());
        }

        if ($missing) {
            $this->newLine();
            $this->error('On the campus list but NOT found on the website (add them in the admin, Programmes, New):');
            foreach ($missing as $m) {
                $this->line('  - '.$m);
            }
        }

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->comment('Dry run: nothing was changed.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($keep) {
            // plain queries on purpose: no file clean-up or other model events
            Programme::whereIn('id', array_keys($keep))->update(['is_active' => true]);
            Programme::whereNotIn('id', array_keys($keep))->update(['is_active' => false]);
        });

        $this->newLine();
        $this->info('Done. Active programmes now: '.Programme::where('is_active', true)->count().'. Hidden ones were not deleted.');

        return self::SUCCESS;
    }
}
