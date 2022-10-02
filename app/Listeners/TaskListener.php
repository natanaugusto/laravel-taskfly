<?php

namespace App\Listeners;

use App\Contracts\ModelEventInterface;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class TaskListener implements ShouldQueue
{
    use InteractsWithQueue;
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
     * @param  \App\Contracts\ModelEventInterface  $event
     * @return void
     */
    public function handle(ModelEventInterface $event)
    {
        $creator = $event->getModel()->creator;
        Notification::send(
            notifiables:[$creator],
            notification:$event->getNotification()
        );
    }
}
