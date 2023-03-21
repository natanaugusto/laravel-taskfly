<?php

use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskComing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;

beforeEach(closure:function () {
    /**
     * @var User $user
     */
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it(description:'put the tasks on queue to be sended as notification', closure:function () {
    $count = 5;
    Task::factory($count)->create([
        'creator_id' => $this->user->id,
        'status' => Status::Todo->value,
        'due' => Carbon::now(),
    ]);
    Notification::fake();
    artisan(command:'task:send-notification')->assertExitCode(0);
    Notification::assertCount(expectedCount:$count);
    Notification::assertSentTo(notifiable:$this->user, notification:TaskComing::class);
});

it(description:'send notification for tasks with an exact due date', closure:function () {
    $secondsDiff = 20;
    $future = Carbon::now()->addSeconds(value:$secondsDiff);
    $task = Task::factory()->createOne([
        'creator_id' => $this->user->id,
        'status' => Status::Todo->value,
        'due' => $future->format(Task::DUE_DATETIME_FORMAT),
    ]);

    Carbon::setTestNow($future->subSeconds(value:$secondsDiff * 2));
    Notification::fake();
    artisan(command:'task:send-notification')->assertExitCode(0);
    Notification::assertNothingSent();

    Carbon::setTestNow($task->due);
    Notification::fake();
    artisan(command:'task:send-notification')->assertExitCode(0);
    Notification::assertSentTo(notifiable:$this->user, notification:TaskComing::class);
});
