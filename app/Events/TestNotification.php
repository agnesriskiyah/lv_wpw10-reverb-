<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $message = 'Notifikasi Reverb berhasil!'
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'test.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
