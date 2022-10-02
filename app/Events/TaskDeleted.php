<?php

namespace App\Events;

use App\Notifications\TaskChanged;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskDeleted extends ModelEventAbstract
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    protected string $notification = TaskChanged::class;
}
