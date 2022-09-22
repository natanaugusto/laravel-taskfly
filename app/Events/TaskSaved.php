<?php

namespace App\Events;

use App\Entities\Task;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskSaved
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Task $task)
    {
    }
}
