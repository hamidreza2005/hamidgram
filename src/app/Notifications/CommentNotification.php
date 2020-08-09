<?php

namespace App\Notifications;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;
    private $commnetor;
    private $post;
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($commnetor,$post,$comment)
    {
        $this->commnetor = $commnetor;
        $this->post = $post;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->setting->notify_when_get_like ? ['database'] : [];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'commentor' =>new UserResource($this->commnetor),
            'post' =>new PostResource($this->post),
            'comment' =>new CommentResource($this->comment),
        ];
    }
}
