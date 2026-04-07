<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnquiryAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $sellerId, public array $payload) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('seller.' . $this->sellerId);
    }

    public function broadcastAs(): string
    {
        return 'enquiry.alert';
    }
}
