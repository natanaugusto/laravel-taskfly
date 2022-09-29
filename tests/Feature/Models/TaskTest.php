<?php

use App\Events\TaskSaved;
use App\Listeners\TaskListener;
use App\Mail\TaskChanged;
use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskSaved as NotificationsTaskSaved;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(closure:function () {
    /**
     * @var User $user
     */
    $this->user = User::factory()->create();
    actingAs($this->user);
});


it(description:'has CRUD', closure:function () {
    $task = Task::factory()->create();
    assertDatabaseHas(table:Task::class, data:$task->toArray());

    $nTitle = 'New Title';
    $task->title = $nTitle;
    $task->save();
    assertDatabaseHas(table:Task::class, data:['id' => $task->id, 'title' => $nTitle]);

    $task->delete();
    assertSoftDeleted(table:Task::class, data:['id' => $task->id]);
});

it(description:'dispatch an event after save', closure:function () {
    Event::fake();
    $task = Task::factory()->makeOne();
    $task->save();
    Event::assertDispatched(event:TaskSaved::class);
    Event::assertListening(expectedEvent:TaskSaved::class, expectedListener:TaskListener::class);
});

it(description:'send a notification as email after create a task', closure:function () {
    Notification::fake();
    $task = Task::factory()->makeOne(['creator_id' => $this->user->id]);
    $task->save();
    Notification::assertSentTo(
        notifiable:[$this->user],
        notification:NotificationsTaskSaved::class,
        callback:function (NotificationsTaskSaved $notification) use ($task) {
            expect(value:$notification->task->id)->toBe($task->id);
            expect(value:$notification->toMail(notifiable:$this->user))->toBeInstanceOf(TaskChanged::class);
            return true;
        }
    );
});

it(description:'enqueue a task listener when a task is saved', closure:function () {
    Queue::fake();
    $task = Task::factory()->makeOne();
    $task->save();
    Queue::assertPushed(
        job:CallQueuedListener::class,
        callback:fn ($job) => $job->class === TaskListener::class
    );
});

it(description:'can mass assignment', closure:function () {
    $task = Task::create(Arr::only(
        array:Task::factory()->makeOne()->toArray(),
        keys:['creator_id', 'title']
    ));
    expect(value:$task)->toBeInstanceOf(Task::class);
    expect(value:$task->exists)->toBeTruthy();
    assertDatabaseHas(table:Task::class, data:$task->toArray());

    $task = Task::factory()->makeOne()->toArray();
    $task['status'] = 'done';

    $task = Task::create($task);
    $task->refresh();
    expect(value:$task->status)->toBe(expected:'todo');
});

it(description:'has creator relationship', closure:function () {
    $task = Task::factory()->create();
    expect(value:$task->creator)->toBeInstanceOf(User::class);

    $users = User::factory()->count(3)->make();
    $task->users()->saveMany($users);
    expect(value:$task->users->toArray())->toHaveCount(3);

    $task->users()->save(User::factory()->create());
    $task->refresh();
    expect(value:$task->users)->toHaveCount(4);
});
