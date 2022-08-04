<?php

use App\Http\Livewire\TaskTable;
use Livewire\Livewire;

it(description:'Mount Livewire TaskTable', closure:function () {
    $component = Livewire::test(name:TaskTable::class);
    $component->assertHasNoErrors();
    $instance = $component->instance();
    expect(value:$instance)->toBeInstanceOf(class :TaskTable::class);
    expect(value:$instance->getPrimaryKey())
        ->toBe(expected:TaskTable::PRIMARY_KEY);

    $columns = $instance->columns();
    foreach ($columns as $value)
    {
        expect(value:$instance->getThAttributes(column:$value))
            ->toBe(expected:TaskTable::TABLE_TH_ATTRS);
    }
    expect(value:$instance->getTableWrapperAttributes())
        ->toBe(expected:TaskTable::TABLE_WRAPPER_ATTRS);
    expect(value:$instance->getSearchStatus())->toBeFalse();
    expect(value:$instance->getColumnSelectStatus())->toBeFalse();
});
