<?php

namespace App\Console\Commands;

use App\Models\Scopes\ImCreatorScope;
use App\Models\Task;
use Illuminate\Console\Command;

class SendTaskNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:send-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the task notification on queue to be sended';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::withoutGlobalScope(ImCreatorScope::class)->get();
        return 0;
    }
}
