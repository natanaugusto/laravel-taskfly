<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskDeleted extends ModelEventAbstract
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
