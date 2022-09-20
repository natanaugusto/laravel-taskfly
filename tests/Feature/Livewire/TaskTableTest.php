<?php

use App\Http\Livewire\Modals\Confirm;
use App\Models\User;
use App\Models\Task;
use App\Http\Livewire\TaskTable;
use Livewire\Testing\TestableLivewire;

use function Pest\Faker\faker;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    /**
     * @var TestableLivewire $component
     * @var TaskTable $instance
     */
    extract(createLivewireComponentInstance(name:TaskTable::class));
    $this->component = $component;
    $this->instance = $instance;
});

it(description:'mounts', closure:function () {
    expect(value:$this->instance->getPrimaryKey())
        ->toBe(expected:TaskTable::PRIMARY_KEY);
    expect(value:$this->instance->getTableAttributes()['class'])->toContain(needles:TaskTable::TABLE_ATTRS['class']);
    expect(value:$this->instance->getTableWrapperAttributes())
        ->toBe(expected:TaskTable::TABLE_WRAPPER_ATTRS);
    expect(value:$this->instance->getSearchStatus())->toBeTrue();
    expect(value:$this->instance->getColumnSelectStatus())->toBeFalse();
});

it(description:'has a loadable page', closure:function () {
    /**
     * @var Collection|User
     */
    $user = User::factory()->create();
    $tasks = Task::factory(count:50)->create();

    $response = actingAs($user)->get(route(name:'tasks'));
    $response->assertViewIs(value:'tasks');
    $response->assertSee(__(key:'Tasks'));
    $response->assertSeeLivewire(component:'task-table');

    $columns = getTableColumns($this->instance, $this->component);
    $columnsArr = array_map(
        callback:static fn ($column) => $column->getTitle(),
        array:$columns
    );
    foreach ($columnsArr as $column) {
        $response->assertSee(__(key:$column));
    }

    $columnsArr = array_map(
        callback:static fn ($column) => $column->getFrom(),
        array:$columns
    );
    foreach ($tasks->chunk(size:$this->instance->getPerPage())[0] as $task) {
        foreach ($task->toArray() as $attr => $val) {
            if (in_array(needle:$attr, haystack:$columnsArr)) {
                $response->assertSee(__(key:$val));
            }
        }
    }
});

it(description:'saves(Create)', closure:function () {
    $task = Task::factory()->makeOne();
    $this->component->set('model', $task);
    $this->component->assertSet('model', $task);
    $this->component->call('save', $task);
    assertDatabaseHas(table:Task::class, data:$task->toArray());
});

it(description:'delete', closure:function () {
    $task = Task::factory()->createOne();
    assertDatabaseHas(table:Task::class, data:$task->toArray());
    $this->component->call('delete', $task);
    assertSoftDeleted(table:Task::class, data:['id' => $task->id]);
});

it(description:'delete with modal confirmation', closure:function () {
    /**
     * @var TestableLivewire $component
     * @var Confirm $instance
     */
    extract(array:createLivewireComponentInstance(
        name:Confirm::class,
        params:[
            'title' => faker()->title,
            'description' => faker()->text(),
            'confirmBtnLabel' => 'Delete',
        ]
    ));

    $task = Task::factory()->createOne();
    $component->set('confirmAction', [
        TaskTable::class,
        'delete',
        $task,
        'refreshDatatable',
    ]);
    $component->call('confirm');
    $component->assertEmitted('refreshDatatable');
    assertSoftDeleted(table:Task::class, data:['id' => $task->id]);
});


function getTableColumns(TaskTable $instance, TestableLivewire $component): array
{
    $columns = $instance->columns();
    expect(value:$columns)->toBeArray();
    foreach ($columns as $column) {
        $component->assertSee(__(key:$column->getTitle()));
    }

    return $columns;
}
