<?php

use App\Models\Task;
use App\Http\Livewire\TaskTable;
use App\Http\Livewire\Modals\Confirm;
use Livewire\Testing\TestableLivewire;

use function Pest\Faker\faker;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->attrs = [
        'title' => faker()->title,
        'description' => faker()->text(),
        'confirmBtnLabel' => 'Delete',
    ];
    /**
     * @var TestableLivewire $component
     * @var Confirm $instance
     */
    extract(array:createLivewireComponentInstance(
        name:Confirm::class,
        params:$this->attrs
    ));
    $this->component = $component;
    $this->instance = $instance;
});

it(description:'mount', closure:function () {
    $this->component
        ->assertViewIs(name:'livewire.modals.confirm')
        ->assertOk();

    foreach ($this->attrs as $val) {
        $this->component->assertSee($val);
    }
});

it(description:'call confirm - TaskTable::delete', closure:function () {
    $Task = Task::factory()->createOne();
    $this->component->set('confirmAction', [
        TaskTable::class,
        'delete',
        $Task,
        'refreshDatatable'
    ]);
    $this->component->call('confirm');
    $this->component->assertEmitted('refreshDatatable');
    assertSoftDeleted(table:Task::class, data:['id' => $Task->id]);
});
