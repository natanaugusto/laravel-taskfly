<?php

namespace App\Listeners;

use App\Events\TaskSaved;
use App\Notifications\ModelEvent;
use App\Notifications\TaskSaved as NotificationsTaskSaved;

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
     * @param  \App\Events\TaskSaved  $event
     * @return void
     */
    public function handle(TaskSaved $event)
    {
        $creator = $event->model->creator;
        Notification::send(
            notifiables:[$creator],
            notification:new ModelEvent($event)
        );
    }
}
