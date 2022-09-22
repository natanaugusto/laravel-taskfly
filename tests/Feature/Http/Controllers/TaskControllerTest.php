<?php

use App\Models\User;
use App\Entities\Task;

use Illuminate\Support\Arr;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use function Pest\Laravel\json;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

it(description:'has a GET@/api/task to list all tasks', closure:function () {
    /**
     * @var User $user
     */
    $user = User::factory()->create();
    actingAs($user);
    $response = json(method:SymfonyRequest::METHOD_GET, uri:route(name:'task.all'));
    $response->assertStatus(status:SymfonyResponse::HTTP_NO_CONTENT)
        ->assertNoContent();
    $response = json(
        method:SymfonyRequest::METHOD_GET,
        uri:route(name:'task.view', parameters:['task' => 1])
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_NOT_FOUND);

    Task::factory()->count(10)->create();
    $tasks = Task::factory()->count(5)->create(['creator_id' => $user->id]);
    $response = json(method:SymfonyRequest::METHOD_GET, uri:route(name:'task.all'));
    $response->assertStatus(status:SymfonyResponse::HTTP_OK);
    // ->assertJsonFragment(data:['data' => $tasks->toArray()]);

    $response = json(
        method:SymfonyRequest::METHOD_GET,
        uri:route(name:'task.view', parameters:['task' => $tasks[1]])
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_OK)
        ->assertJsonFragment(data:$tasks[1]->toArray());
});

it(description:'has a POST@/api/task to create a task', closure:function () {
    /**
     * @var User $user
     */
    $user = User::factory()->create();
    actingAs($user);
    $response = json(
            method:SymfonyRequest::METHOD_POST,
            uri:route(name:'task.store'),
            data:[]
        );
    $response->assertStatus(status:SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);

    $task = Arr::only(
        array:Task::factory()->makeOne()->toArray(),
        keys:['title', 'due']
    );
    $response = json(
        method:SymfonyRequest::METHOD_POST,
        uri:route(name:'task.store'),
        data:$task
    );
    $task['creator_id'] = $user->id;
    $response->assertStatus(status:SymfonyResponse::HTTP_CREATED)
        ->assertJsonFragment(
            data:$task
        );
    assertDatabaseHas(table:Task::class, data:$task);
});

it(description:'has an UPDATE@/api/task to update a task', closure:function () {
    /**
     * @var User $user
     */
    $user = User::factory()->create();
    actingAs($user);
    $task = Task::factory()->create();
    $response = json(
            method:SymfonyRequest::METHOD_PUT,
            uri:route(name:'task.update', parameters:['task' => $task]),
            data:$task->toArray()
        );
    $response->assertStatus(status:SymfonyResponse::HTTP_NOT_MODIFIED);

    $nTitle = 'New Task Title';
    $response = json(
        method:SymfonyRequest::METHOD_PUT,
        uri:route(name:'task.update', parameters:['task' => $task]),
        data:['title' => $nTitle]
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_ACCEPTED)
        ->assertJsonFragment(['title' => $nTitle]);
    assertDatabaseHas(table:Task::class, data:['id' => $task->id, 'title' => $nTitle]);
});

it(description:'has a DELETE@/api/task to delete a task', closure:function () {
    /**
     * @var User $user
     */
    $user = User::factory()->create();
    actingAs($user);
    $task = Task::factory()->create(['creator_id' => $user->id]);
    $response = json(
        method:SymfonyRequest::METHOD_DELETE,
        uri:route(name:'task.delete', parameters:['task' => $task])
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_ACCEPTED);
});

it(description:'can create a task POST@/api/task with users related', closure:function () {
    $task = Arr::only(
        array:Task::factory()->makeOne()->toArray(),
        keys:['title', 'due']
    );
    $users = User::factory()->count(3)->create();
    $task['users'] = $users->pluck('id')->all();
    /**
     * @var User $user
     */
    $user = User::factory()->create();
    actingAs($user);
    $response = json(
        method:SymfonyRequest::METHOD_POST,
        uri:route(name:'task.store'),
        data:$task
    );
    unset($task['users']);
    $task['creator_id'] = $user->id;
    $response->assertStatus(status:SymfonyResponse::HTTP_CREATED)
        ->assertJsonFragment(
            data:$task
        );
    assertDatabaseHas(table:Task::class, data:$task);
});

it(description:'has a PUT@/api/task/relate to relate user to a existent task', closure:function () {
    $task = Task::factory()->create();
    $users = User::factory()->count(3)->create();
    /**
     * @var User $user
     */
    $user = User::factory()->create();
    actingAs($user);
    $response = json(
        method:SymfonyRequest::METHOD_PUT,
        uri:route(name:'task.relate', parameters:['task' => $task]),
        data:[
            'users' => $users->pluck('id')->all()
        ]
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_ACCEPTED);
    $task->refresh();
    $taskUsers = array_map(callback:fn ($user) => Arr::except(
        array:$user,
        keys:['pivot']
    ), array:$task->users->toArray());
    expect(value:$taskUsers)->toEqual(expected:$users->toArray());
});
