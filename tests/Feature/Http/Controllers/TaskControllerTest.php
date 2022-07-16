<?php

use App\Models\Task;
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
