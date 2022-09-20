<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Arr;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;

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
