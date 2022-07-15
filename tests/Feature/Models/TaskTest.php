<?php
use App\Models\Task;

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
