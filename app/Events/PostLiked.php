<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PostLiked implements ShouldBroadcast
{
    use SerializesModels;

    public $post;
    public $likesCount;

    public function __construct($post)
    {
        $this->post = $post;
        $this->likesCount = $post->likes_count;
    }

    public function broadcastOn()
    {
        return new Channel('post.' . $this->post->id);
    }
}
