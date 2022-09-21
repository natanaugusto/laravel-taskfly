<?php

namespace App\Listeners;

use App\Mail\TaskChanged;
use App\Events\TaskSaved;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $mailable = new TaskChanged($event->task);
        Mail::to(users:[$event->task->creator])->queue($mailable);
    }
}
