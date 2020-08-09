<?php

namespace App\Notifications;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
{
    use Queueable;
    private $post;
    private $liker;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($post,$liker)
    {
        $this->post = $post;
        $this->liker = $liker;
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
            "message"=>"Some One Like Your Post",
            "post" => new PostResource($this->post),
            "Liker" => new UserResource($this->liker),
        ];
    }
}
