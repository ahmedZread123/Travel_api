<?php

namespace App\Listeners;

use App\Events\Message_event;
use App\Models\message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use SebastianBergmann\Environment\Console;

class message_listener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Message_event  $event
     * @return void
     */
    public function handle(Message_event $event)
    {

    //    return $event->message;
    }
}
