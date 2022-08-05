<?php
use App\Http\Livewire\TaskTable;
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

it(description:'test Livewire\TaskTable', closure:function () {
    /**
     * @var Collection|User
     */
    $user = User::factory()->create();
    $response = actingAs($user)->get(route(name:'tasks.index'));
    $response->assertViewIs(value:'tasks.index');
    $response->assertSee(__(key:'Tasks'));
    $response->assertSeeLivewire(component:'task-table');

    extract(createLivewireComponentInstance(name:TaskTable::class));
    $columns = array_map(
        callback:static fn($column) => $column->getTitle(),
        array:getTableColumns($instance, $component)
    );
    expect(value:$columns)->toBeArray();
    foreach ($columns as $column) {
        $response->assertSee(__(key:$column));
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
