<?php
use App\Models\User;
use App\Models\Task;

use Illuminate\Support\Arr;

test(description: 'Task Model/Factory CRUD', closure: function () {
    $task = Task::factory()->create();
    $this->assertDatabaseHas(table: Task::class, data: $task->toArray());

    $nTitle = 'New Title';
    $task->title = $nTitle;
    $task->save();
    $this->assertDatabaseHas(table: Task::class, data: ['id' => $task->id, 'title' => $nTitle]);

    $task->delete();
    $this->assertDatabaseMissing(table: Task::class, data: ['id' => $task->id]);
});

test(description: 'Task mass assignments', closure: function () {
    $task = Task::create(Arr::only(
        array: Task::factory()->makeOne()->toArray(),
        keys: ['creator_id', 'title']
    ));
    $this->assertInstanceOf(expected: Task::class, actual: $task);
    $this->assertTrue(condition: $task->exists);
    $this->assertDatabaseHas(table: Task::class, data: $task->toArray());

    $task = Task::factory()->makeOne()->toArray();
    $task['status'] = 'done';

    $task = Task::create($task);
    $task->refresh();
    $this->assertEquals(expected: 'todo', actual: $task->status);
});

test(description: 'Creator relationship', closure: function () {
    $task = Task::factory()->create();
    $this->assertInstanceOf(expected: User::class, actual: $task->creator);

    $users = User::factory()->count(3)->make();
    $task->users()->saveMany($users);
    $this->assertCount(expectedCount: 3, haystack: $task->users);

    $task->users()->save(User::factory()->create());
    $task->refresh();
    $this->assertCount(expectedCount: 4, haystack: $task->users);
});
