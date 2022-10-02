<?php

namespace App\Events;

use App\Models\Task;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskSaved extends BaseModel
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;
}
