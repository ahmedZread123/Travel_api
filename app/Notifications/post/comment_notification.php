<?php

namespace App\Notifications\post;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class comment_notification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public  $comment ;
    public $user ;
    public function __construct($comment , $user)
    {
        $this->comment = $comment;
       $this->user = $this->user ;
    }




    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database' , 'broadcast'];
    }



    public function toArray($notifiable)
    {
        return [
            // 'user_name' => $this->user->name,
            // 'user_photo' => $this->user->photo,
            'user_id' => $this->user['id'],
            'post_id' => $this->comment->post_id,
            'created_at' => $this->comment->created_at,
            'notifiable_id' => $this->user['id'],
            'type' => 'comment',
        ];
    }

    public function toBrodcast($notifiable)
    {
        return new BroadcastMessage([
           'data' => [
            // 'user_name' => $this->user->name,
            // 'user_photo' => $this->user->photo,
            'user_id' => $this->user['id'],
            'post_id' => $this->comment->post_id,
            'created_at' => $this->comment->created_at,
            'type' => 'comment',

           ]
        ]);

    }


}
