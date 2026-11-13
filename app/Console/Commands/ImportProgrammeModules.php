<?php

namespace App\Console\Commands;

use App\Models\Programme;
use Illuminate\Console\Command;

/**
 * Fills each programme's modules (semester by semester) from database/data/programme-modules.php, which was
 * copied from the programme brochures. Programmes that already have modules are left alone (so changes made in
 * the admin are never lost) unless --force is given.
 *
 * php artisan programmes:import-modules --dry-run    shows what would be filled, changes nothing
 * php artisan programmes:import-modules              fills the empty ones
 * php artisan programmes:import-modules --force      replaces the modules of every listed programme
 */
class ImportProgrammeModules extends Command
{
    protected $signature = 'programmes:import-modules {--dry-run : Only show what would change} {--force : Also replace programmes that already have modules}';

    protected $description = 'Load the semester-by-semester modules (from the brochures) into the programmes.';

    public function handle(): int
    {
        $data = require database_path('data/programme-modules.php');
        $rows = [];
        $missing = [];

        foreach ($data as [$level, $title, $semesters]) {
            $programme = Programme::where('level', $level)->whereRaw('LOWER(title) = ?', [mb_strtolower($title)])->first();

            if (! $programme) {
                $missing[] = "{$level}: {$title}";
                continue;
            }

            $modules = [];
            foreach ($semesters as $semester => $titles) {
                foreach ($titles as $moduleTitle) {
                    $modules[] = ['semester' => (int) $semester, 'title' => $moduleTitle];
                }
            }

            $has = count($programme->modules ?? []);
            $action = match (true) {
                $has === 0 => 'fill',
                (bool) $this->option('force') => 'replace',
                default => 'keep',
            };

            $rows[] = [$programme, $modules, $action, $has, count($semesters)];
        }

        $this->table(['Level', 'Programme', 'Semesters', 'Modules', 'Has now', 'Action'], collect($rows)->map(fn ($r) => [
            $r[0]->level, $r[0]->title, $r[4], count($r[1]), $r[3], match ($r[2]) {
                'fill' => 'will be filled',
                'replace' => 'will be REPLACED',
                default => 'kept (already has modules)',
            },
        ])->all());

        if ($missing) {
            $this->warn('Not found on the website (skipped):');
            foreach ($missing as $m) {
                $this->line('  - '.$m);
            }
        }

        if ($this->option('dry-run')) {
            $this->comment('Dry run: nothing was changed.');

            return self::SUCCESS;
        }

        $changed = 0;
        foreach ($rows as [$programme, $modules, $action]) {
            if ($action === 'keep') {
                continue;
            }
            $programme->update(['modules' => $modules]);
            $changed++;
        }

        $this->info("Done. Modules saved for {$changed} programme(s).");

        return self::SUCCESS;
    }
}
