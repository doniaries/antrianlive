<?php

namespace App\Events;

use App\Models\Counter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CounterStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $counter;

    /**
     * Create a new event instance.
     */
    public function __construct(Counter $counter)
    {
        $this->counter = $counter->load('services');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('counters'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'counter.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'counter' => $this->counter,
            'is_available' => $this->counter->isAvailable(),
            'status_text' => $this->getStatusText($this->counter),
            'status_class' => $this->getStatusClass($this->counter)
        ];
    }

    private function getStatusText($counter)
    {
        if ($counter->status === 'tutup') {
            return 'Tutup';
        } elseif ($counter->status === 'istirahat') {
            return 'Istirahat';
        }
        return $counter->isAvailable() ? 'Buka' : 'Tutup';
    }

    private function getStatusClass($counter)
    {
        if ($counter->status === 'tutup') {
            return 'status-closed';
        } elseif ($counter->status === 'istirahat') {
            return 'status-break';
        }
        return $counter->isAvailable() ? 'status-open' : 'status-closed';
    }
}
