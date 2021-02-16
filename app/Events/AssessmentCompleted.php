<?php

namespace App\Events;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssessmentCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $assessmentId;

    public $attemptId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($assessmentId, $attemptId)
    {
        $this->assessmentId = $assessmentId;
        $this->attemptId = $attemptId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('assessment.' . $this->assessmentId);
    }
}
