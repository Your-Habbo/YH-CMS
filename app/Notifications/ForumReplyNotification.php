<?php

namespace App\Notifications;

class ForumReplyNotification
{
    protected $thread;
    protected $reply;

    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    public function toArray()
    {
        return [
            'thread_id' => $this->thread->id,
            'thread_title' => $this->thread->title,
            'reply_id' => $this->reply->id,
            'replier_name' => $this->reply->user->name,
        ];
    }
}