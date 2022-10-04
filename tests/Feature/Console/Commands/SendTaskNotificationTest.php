<?php

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskComing;

use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;

beforeEach(closure:function () {
    $this->user = User::factory()->createOne();
});

it(description:'put the tasks on queue to be sended as notification', closure:function () {
    actingAs($this->user);
    $count = 15;
    Task::factory($count)->create();
    artisan(command:'task:send-notification')->assertExitCode(0);
});
