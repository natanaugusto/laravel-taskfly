<?php

use App\Http\Livewire\TaskForm;

it(description: 'test mount', closure:function () {
    /**
     * @var TestableLivewire $component
     * @var TaskForm $instance
     */
    extract(createLivewireComponentInstance(name:TaskForm::class));
    $component->assertSee(__(key:'Title'));
    $component->assertSee(__(key:'Due'));
});
