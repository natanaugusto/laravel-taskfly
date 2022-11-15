<?php

namespace App\Console\Commands;

use App\Models\Scopes\ImCreatorScope;
use App\Models\Task;
use App\Notifications\TaskComing;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Notification;

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
        $tasks = Task::withoutGlobalScope(ImCreatorScope::class)
            ->todo()
            ->orderByDesc('due')
            ->get();

        foreach ($tasks as $task) {
            $now = Carbon::now();
            if ($now->greaterThanOrEqualTo(date:$task->due)) {
                Notification::send(
                    notifiables:[$task->creator],
                    notification:new TaskComing($task)
                );
            }
        }
        return 0;
    }
}
