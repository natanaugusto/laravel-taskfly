<?php

use App\Models\Task;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

test(description: 'Task Controller/API Read', closure: function () {
    $response = $this->json(method: SymfonyRequest::METHOD_GET, uri: route(name: 'task.all'));
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
        ->assertJson(value: $tasks->toArray());

    $response = $this->json(
        method: SymfonyRequest::METHOD_GET,
        uri: route(name: 'task.view', parameters: ['task' => $tasks[1]])
    );
    $response->assertStatus(status: SymfonyResponse::HTTP_OK)
        ->assertJson(value: $tasks[1]->toArray());
});

test(description: 'Task Controller/API Create', closure: function () {
   $response = $this->json(
       method: SymfonyRequest::METHOD_POST,
       uri: route(name: 'task.store'),
       data: []
   );
   $response->assertStatus(status: SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);

   $task = Arr::only(
       array:Task::factory()->makeOne()->toArray(),
       keys: ['owner_id', 'title', 'due']
   );
    $response = $this->json(
        method: SymfonyRequest::METHOD_POST,
        uri: route(name: 'task.store'),
        data: $task
    );
    $response->assertStatus(status: SymfonyResponse::HTTP_CREATED)
        ->assertJsonFragment(
            data: $task
        );
    $this->assertDatabaseHas(table: Task::class, data: $task);
});
