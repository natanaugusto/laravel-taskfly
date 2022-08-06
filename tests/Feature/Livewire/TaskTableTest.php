<?php
use App\Http\Livewire\TaskTable;
use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\actingAs;
use Livewire\Testing\TestableLivewire;

it(description:'test Livewire\TaskTable mount', closure:function () {
    extract(createLivewireComponentInstance(name:TaskTable::class));
    expect(value:$instance)->toBeInstanceOf(class :TaskTable::class);
    expect(value:$instance->getPrimaryKey())
        ->toBe(expected:TaskTable::PRIMARY_KEY);

    $columns = getTableColumns($instance, $component);
    foreach ($columns as $column)
    {
        expect(value:$instance->getThAttributes(column:$column))
            ->toBe(expected:TaskTable::TABLE_TH_ATTRS);
    }
    expect(value:$instance->getTableWrapperAttributes())
        ->toBe(expected:TaskTable::TABLE_WRAPPER_ATTRS);
    expect(value:$instance->getSearchStatus())->toBeFalse();
    expect(value:$instance->getColumnSelectStatus())->toBeFalse();
});

it(description:'test tasks.index', closure:function () {
    /**
     * @var Collection|User
     */
    $user = User::factory()->create();
    $tasks = Task::factory(count:50)->create();

    $response = actingAs($user)->get(route(name:'tasks.index'));
    $response->assertViewIs(value:'tasks.index');
    $response->assertSee(__(key:'Tasks'));
    $response->assertSeeLivewire(component:'task-table');

    extract(createLivewireComponentInstance(name:TaskTable::class));
    $columns = getTableColumns($instance, $component);
    $columnsArr = array_map(
        callback:static fn($column) => $column->getTitle(),
        array:$columns
    );
    foreach ($columnsArr as $column) {
        $response->assertSee(__(key:$column));
    }

    $columnsArr = array_map(
        callback:static fn($column) => $column->getFrom(),
        array:$columns
    );
    foreach ($tasks->chunk(size:$instance->getPerPage())[0] as $task) {
        foreach ($task->toArray() as $attr => $val) {
            if (in_array(needle:$attr, haystack:$columnsArr)) {
                $response->assertSee(__(key:$val));
            }
        }
    }
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
