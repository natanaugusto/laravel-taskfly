<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TaskCreated extends BaseModel
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public string $markdownView = 'mail.task-created';
}
