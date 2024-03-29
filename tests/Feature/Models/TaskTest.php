<?php

use App\Enums\Status;
use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Listeners\TaskListener;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ModelEvent;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
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

it(description:'dispatch an event after task changes', closure:function () {
    Event::fake();
    $task = Task::factory()->makeOne();
    $task->save();
    Event::assertDispatched(event:TaskCreated::class);
    Event::assertListening(expectedEvent:TaskCreated::class, expectedListener:TaskListener::class);

    $task->title = 'NewTitle';
    $task->save();
    Event::assertDispatched(event:TaskUpdated::class);
    Event::assertListening(expectedEvent:TaskUpdated::class, expectedListener:TaskListener::class);

    $task->delete();
    Event::assertDispatched(event:TaskDeleted::class);
    Event::assertListening(expectedEvent:TaskDeleted::class, expectedListener:TaskListener::class);
});

//TODO: Remove duplicated code
it(description:'send a notification as email after create a task', closure:function () {
    Notification::fake();
    $task = Task::factory()->makeOne(['creator_id' => $this->user->id]);
    $task->save();
    Notification::assertSentTo(
        notifiable:[$this->user],
        notification:ModelEvent::class,
        callback:function (ModelEvent $notification) use ($task) {
            assertModelEvent($notification, $this->user, model:$task);

            return true;
        }
    );

    $task->title = 'NewTitle';
    Notification::assertSentTo(
        notifiable:[$this->user],
        notification:ModelEvent::class,
        callback:function (ModelEvent $notification) use ($task) {
            assertModelEvent($notification, $this->user, model:$task);

            return true;
        }
    );

    $task->delete();
    Notification::assertSentTo(
        notifiable:[$this->user],
        notification:ModelEvent::class,
        callback:function (ModelEvent $notification) use ($task) {
            assertModelEvent($notification, $this->user, model:$task);

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
    expect(value:$task->status)->toBe(expected:Status::Todo->value);
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

it(description:'has todo scope', closure:function () {
    $todoCount = 5;
    Task::factory(count:$todoCount)->create([
        'creator_id' => $this->user->id,
        'status' => Status::Todo->value,
    ]);
    Task::factory(count:3)->create([
        'creator_id' => $this->user->id,
        'status' => Status::Doing->value,
    ]);
    $scoped = Task::todo()->get();
    expect(value:$scoped->count())->toBe(expected:$todoCount);
});
