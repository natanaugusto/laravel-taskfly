<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

test(description: 'Task Controller/API Read', closure: function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->json(method: SymfonyRequest::METHOD_GET, uri: route(name: 'task.all'));
    $response->assertStatus(status: SymfonyResponse::HTTP_NO_CONTENT)
        ->assertNoContent();
    $response = $this->json(
        method: SymfonyRequest::METHOD_GET,
        uri: route(name: 'task.view', parameters: ['task' => 1])
    );
    $response->assertStatus(status: SymfonyResponse::HTTP_NOT_FOUND);

    $tasks = Task::factory()->count(10)->create();
    $response = $this->json(method: SymfonyRequest::METHOD_GET, uri: route(name: 'task.all'));
    $response->assertStatus(status: SymfonyResponse::HTTP_OK)
        ->assertJsonFragment(data: ['data' => $tasks->toArray()]);

    $response = $this->json(
        method: SymfonyRequest::METHOD_GET,
        uri: route(name: 'task.view', parameters: ['task' => $tasks[1]])
    );
    $response->assertStatus(status: SymfonyResponse::HTTP_OK)
        ->assertJsonFragment(data: $tasks[1]->toArray());
});

test(description: 'Task Controller/API Create', closure: function () {
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->json(
            method: SymfonyRequest::METHOD_POST,
            uri: route(name: 'task.store'),
            data: []
        );
    $response->assertStatus(status: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);

    $task = Arr::only(
        array: Task::factory()->makeOne()->toArray(),
        keys: ['title', 'due']
    );
    $response = $this->json(
        method: SymfonyRequest::METHOD_POST,
        uri: route(name: 'task.store'),
        data: $task
    );
    $task['creator_id'] = $user->id;
    $response->assertStatus(status: SymfonyResponse::HTTP_CREATED)
        ->assertJsonFragment(
            data: $task
        );
    $this->assertDatabaseHas(table: Task::class, data: $task);
});

test(description: 'Task Controller/API Update', closure: function () {
    $task = Task::factory()->create();
    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->json(
            method: SymfonyRequest::METHOD_PUT,
            uri: route(name: 'task.update', parameters: ['task' => $task]),
            data: $task->toArray()
        );
    $response->assertStatus(status: SymfonyResponse::HTTP_NOT_MODIFIED);
    $nTitle = 'New Task Title';
    $response = $this->json(
        method: SymfonyRequest::METHOD_PUT,
        uri: route(name: 'task.update', parameters: ['task' => $task]),
        data: ['title' => $nTitle]
    );
    $response->assertStatus(status: SymfonyResponse::HTTP_ACCEPTED)
        ->assertJsonFragment(['title' => $nTitle]);
    $this->assertDatabaseHas(table: Task::class, data: ['id' => $task->id, 'title' => $nTitle]);
});

test(description: 'Task Controller/API Delete', closure: function () {
    $task = Task::factory()->create();

    $user = User::factory()->create();
    $response = $this
        ->actingAs($user)
        ->json(
            method: SymfonyRequest::METHOD_DELETE,
            uri: route(name: 'task.delete', parameters: ['task' => $task])
        );
    $response->assertStatus(status: SymfonyResponse::HTTP_ACCEPTED);
});

test(description: 'Create Task with users related', closure: function () {
    $task = Arr::only(
        array: Task::factory()->makeOne()->toArray(),
        keys: ['title', 'due']
    );
    $users = User::factory()->count(3)->create();
    $task['users'] = $users->pluck('id')->all();
    $user = User::factory()->create();
    $response = $this->actingAs($user)
        ->json(
            method: SymfonyRequest::METHOD_POST,
            uri: route(name: 'task.store'),
            data: $task
        );
    unset($task['users']);
    $task['creator_id'] = $user->id;
    $response->assertStatus(status: SymfonyResponse::HTTP_CREATED)
        ->assertJsonFragment(
            data: $task
        );
    $this->assertDatabaseHas(table: Task::class, data: $task);
});

test(description: 'Relate users with a Task', closure: function () {
    $task = Task::factory()->create();
    $users = User::factory()->count(3)->create();
    $user = User::factory()->create();
    $response = $this->actingAs($user)
        ->json(
            method: SymfonyRequest::METHOD_PUT,
            uri: route(name: 'task.relate', parameters: ['task' => $task]),
            data: [
                'users' => $users->pluck('id')->all()
            ]
        );
    $response->assertStatus(status: SymfonyResponse::HTTP_ACCEPTED);
    $task->refresh();
    $taskUsers = array_map(callback: fn ($user) => Arr::except($user, ['pivot']), array: $task->users->toArray());
    $this->assertEquals(expected: $users->toArray(), actual: $taskUsers);
});
