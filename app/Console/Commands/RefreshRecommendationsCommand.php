<?php

namespace App\Console\Commands;

use App\Services\PropertyRecommendationService;
use Illuminate\Console\Command;

class RefreshRecommendationsCommand extends Command
{
    protected $signature = 'recommendations:refresh {--incremental : Refresh only recently updated approved properties} {--since-hours=24 : Look-back window for incremental mode} {--property_id=* : Refresh explicit property IDs}';
    protected $description = 'Recompute property vectors for cosine recommendations';

    public function handle(PropertyRecommendationService $recommendationService): int
    {
        $propertyIds = array_filter(array_map('intval', (array) $this->option('property_id')));
        $incremental = (bool) $this->option('incremental');
        $sinceHours = max(1, (int) $this->option('since-hours'));

        if (!empty($propertyIds)) {
            $count = $recommendationService->refreshPropertyVectors($propertyIds);
            $this->info("Refreshed vectors for {$count} explicit properties.");
            return self::SUCCESS;
        }

        if ($incremental) {
            $count = $recommendationService->refreshChangedPropertyVectors($sinceHours);
            $this->info("Incremental refresh complete. Updated {$count} properties (last {$sinceHours}h).");
            return self::SUCCESS;
        }

        $count = $recommendationService->refreshPropertyVectors();
        $this->info("Full refresh complete. Updated {$count} approved properties.");
        return self::SUCCESS;
    }
}
