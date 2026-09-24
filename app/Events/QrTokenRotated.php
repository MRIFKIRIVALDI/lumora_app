<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QrTokenRotated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(public int $stationId, public string $scanUrl, public string $expiresAt) {}
    public function broadcastOn(): array { return [new Channel('qr-station.'.$this->stationId)]; }
    public function broadcastAs(): string { return 'qr.rotated'; }
}
