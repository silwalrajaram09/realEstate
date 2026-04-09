<?php

namespace App\Jobs;

use App\Models\PropertyView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackPropertyViewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $userId,
        protected int $propertyId
    ) {}

    public function handle(): void
    {
        PropertyView::updateOrCreate(
            ['user_id' => $this->userId, 'property_id' => $this->propertyId],
            ['created_at' => now()]
        );
    }
}
