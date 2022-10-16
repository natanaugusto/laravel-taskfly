<?php

use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskComing;

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
        'status' => Status::Todo->value
    ]);
    Notification::fake();
    artisan(command:'task:send-notification')->assertExitCode(0);
    Notification::assertCount(expectedCount:$count);
    Notification::assertSentTo(notifiable:$this->user, notification:TaskComing::class);
});
