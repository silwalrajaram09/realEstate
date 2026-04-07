<?php

namespace App\Console\Commands;

use App\Services\PropertyRecommendationService;
use Illuminate\Console\Command;

class RecommendationReportCommand extends Command
{
    protected $signature = 'recommendations:report {--sample-users=30 : Number of users to sample} {--limit=6 : Recommendations per user}';
    protected $description = 'Generate offline recommendation quality diagnostics';

    public function handle(PropertyRecommendationService $recommendationService): int
    {
        $sampleUsers = max(1, (int) $this->option('sample-users'));
        $limit = max(1, (int) $this->option('limit'));

        $stats = $recommendationService->diagnostics($sampleUsers, $limit);

        $this->info('Recommendation quality report');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Sample users', $stats['sample_users'] ?? 0],
                ['Avg similarity', $stats['avg_similarity'] ?? 0],
                ['Avg unique categories', $stats['avg_unique_categories'] ?? 0],
                ['Repeat rate', $stats['repeat_rate'] ?? 0],
                ['Coverage rate', $stats['coverage_rate'] ?? 0],
            ]
        );

        return self::SUCCESS;
    }
}
