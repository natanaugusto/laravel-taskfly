<?php

namespace App\Listeners;

use App\Events\TaskSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskListener
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
     * @param  \App\Events\TaskSaved  $event
     * @return void
     */
    public function handle(TaskSaved $event)
    {
        //
    }
}
