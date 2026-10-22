<?php

namespace App\Console\Commands;

use App\Models\Programme;
use App\Support\EntryRequirements;
use Illuminate\Console\Command;

class RefreshRequirements extends Command
{
    protected $signature = 'programmes:refresh-requirements';

    protected $description = 'Rewrite every programme\'s entry requirements in standard wording (by level)';

    public function handle(): int
    {
        $n = 0;
        foreach (Programme::all() as $p) {
            $p->update(['requirements' => EntryRequirements::for($p->level, $p->requirements)]);
            $n++;
        }
        $this->info("Updated {$n} programmes.");

        return self::SUCCESS;
    }
}