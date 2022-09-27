<?php

use App\Models\Task;
use App\Models\User;

use Illuminate\Support\Arr;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use function Pest\Laravel\json;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertEquals;

beforeEach(closure:function () {
    /**
     * @var User $user
     */
    $this->user = User::factory()->create();
    actingAs($this->user);

});

it(description:'has a @get:/task endpoint', closure:function () {
    $response = json(method:SymfonyRequest::METHOD_GET, uri:route(name:'task.all'));
    $response->assertStatus(status:SymfonyResponse::HTTP_NO_CONTENT)
        ->assertNoContent();
    $response = json(
        method:SymfonyRequest::METHOD_GET,
        uri:route(name:'task.view', parameters:['task' => 1])
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_NOT_FOUND);

    Task::factory(count:15)->create();
    $tasks = Task::factory(count:10)->create([
        'creator_id' => $this->user->id
    ]);
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

it(description:'has a @post:/task endpoint', closure:function () {
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
    $task['creator_id'] = $this->user->id;
    $response->assertStatus(status:SymfonyResponse::HTTP_CREATED)
        ->assertJsonFragment(
            data:$task
        );
    assertDatabaseHas(table:Task::class, data:$task);
});

it(description:'can use @post:/task to create a task with users related', closure:function () {
    $task = Arr::only(
        array:Task::factory()->makeOne()->toArray(),
        keys:['title', 'due']
    );
    $users = User::factory()->count(3)->create();
    $task['users'] = $users->pluck('id')->all();
    $user = User::factory()->create();
    $response = $this->actingAs($user)
        ->json(
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

test(description:'has a @put:/task/relate endpoint ', closure:function () {
    $task = Task::factory()->create(['creator_id' => $this->user->id]);
    $users = User::factory()->count(3)->create();
    $response = json(
        method:SymfonyRequest::METHOD_PUT,
        uri:route(name:'task.relate', parameters:['task' => $task]),
        data:[
            'users' => $users->pluck('id')->all(),
        ]
    );
    $response->assertStatus(status:SymfonyResponse::HTTP_ACCEPTED);
    $task->refresh();
    $taskUsers = array_map(callback:fn($user) => Arr::except($user, ['pivot']), array:$task->users->toArray());
    assertEquals(expected:$users->toArray(), actual:$taskUsers);
});


it(description:'has an @update:/task endpoint', closure:function () {
    $task = Task::factory()->create(['creator_id' => $this->user->id]);
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

it(description:'has a @delete:/task endpoint', closure:function () {
    $task = Task::factory()->create(['creator_id' => $this->user->id]);
    $response = json(
            method:SymfonyRequest::METHOD_DELETE,
            uri:route(name:'task.delete', parameters:['task' => $task])
        );
    $response->assertStatus(status:SymfonyResponse::HTTP_ACCEPTED);
});
