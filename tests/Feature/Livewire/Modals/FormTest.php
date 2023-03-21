<?php

use App\Http\Livewire\Modals\Form;

beforeEach(function () {
    /**
     * @var TestableLivewire $component
     * @var Form $instance
     */
    extract(array:createLivewireComponentInstance(
        name:Form::class,
        params:['inputsView' => 'tasks.inputs']
    ));
    $this->component = $component;
    $this->instance = $instance;
});

it(description:'mount', closure:function () {
    $this->component
        ->assertViewIs(name:'livewire.modals.form')
        ->assertOk();
});
