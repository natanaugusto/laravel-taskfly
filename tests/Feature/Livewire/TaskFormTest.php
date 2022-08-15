<?php

use App\Http\Livewire\TaskForm;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use function Pest\Faker\faker;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

it(description: 'test mount', closure:function () {
    /**
     * @var Model|Authenticatable
     */
    $user = User::factory()->createOne();
    actingAs($user);
    /**
     * @var TestableLivewire $component
     * @var TaskForm $instance
     */
    extract(createLivewireComponentInstance(name:TaskForm::class));
    $component->assertSee(__(key:'Title'));
    $component->assertSee(__(key:'Due'));
    $component->assertHasNoErrors();
    expect(value:$instance->task)->toBeInstanceOf(class:Task::class);
});

it(description:'test create', closure:function () {
    /**
     * @var Model|Authenticatable
     */
    $user = User::factory()->createOne();
    actingAs($user);

    /**
     * @var TestableLivewire $component
     * @var TaskForm $instance
     */
    extract(createLivewireComponentInstance(name:TaskForm::class));
    $title = faker()->title;
    $component
        ->set('task.title', $title)
        ->set('task.due', Carbon::now())
        ->call('save');
    $component->assertHasNoErrors();
    $component->assertRedirect(route(name:'tasks.index'));
    assertDatabaseHas(
        table:Task::class,
        data:['creator_id' => $user->id, 'title' => $title]
    );
});
