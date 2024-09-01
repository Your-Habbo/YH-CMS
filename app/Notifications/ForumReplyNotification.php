<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ForumReplyNotification extends Notification
{
    use Queueable;

    public $thread;
    public $reply;

    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['mail']; // or any other channels you want to use (database, broadcast, etc.)
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new reply has been posted in the thread "' . $this->thread->title . '".')
                    ->action('View Reply', url(route('forum.show', $this->thread->slug)))
                    ->line('Thank you for using our forum!');
    }

    public function toArray($notifiable)
    {
        return [
            'thread_id' => $this->thread->id,
            'reply_id' => $this->reply->id,
            'message' => 'A new reply has been posted in the thread "' . $this->thread->title . '".',
        ];
    }
}
