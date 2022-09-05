<?php

use App\Models\User;
use App\Models\Task;
use App\Http\Livewire\TaskTable;
use Livewire\Testing\TestableLivewire;

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

it(description:'test mount', closure:function () {
    expect(value:$this->instance->getPrimaryKey())
        ->toBe(expected:TaskTable::PRIMARY_KEY);

    expect(value:$this->instance->getTableWrapperAttributes())
        ->toBe(expected:TaskTable::TABLE_WRAPPER_ATTRS);
    expect(value:$this->instance->getSearchStatus())->toBeTrue();
    expect(value:$this->instance->getColumnSelectStatus())->toBeFalse();
});

it(description:'test tasks page', closure:function () {
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

it(description:'test delete', closure:function () {
    $task = Task::factory()->createOne();
    assertDatabaseHas(table:Task::class, data:$task->toArray());
    $this->component->call('delete', $task);
    assertSoftDeleted(table:Task::class, data:$task->toArray());
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
