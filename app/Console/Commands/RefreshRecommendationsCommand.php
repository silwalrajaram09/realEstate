<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshRecommendationsCommand extends Command
{
    protected $signature = 'recommendations:refresh {--incremental} {--since-hours=} {--property_id=*}';
    protected $description = 'Deprecated: No longer required (Simple Hybrid Recommendation is memory-scored)';

    public function handle(): int
    {
        $this->info("✨ Success: The new Simple Hybrid Recommendation Engine runs natively on database queries.");
        $this->info("There is no longer a need to build heavy vector properties in the background.");
        
        return self::SUCCESS;
    }
}
