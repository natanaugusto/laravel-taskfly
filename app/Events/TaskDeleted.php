<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskDeleted extends BaseModel
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;
}
