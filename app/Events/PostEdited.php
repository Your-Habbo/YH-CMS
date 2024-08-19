<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PostEdited implements ShouldBroadcast
{
    use SerializesModels;

    public $post;
    public $threadId;

    public function __construct($post, $threadId)
    {
        $this->post = $post;
        $this->threadId = $threadId;
    }

    public function broadcastOn()
    {
        return new Channel('thread.' . $this->threadId);
    }
}
