<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated extends BaseModel
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public string $markdownView = 'mail.task-created';
}
