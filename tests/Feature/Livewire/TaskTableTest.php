<?php

use App\Http\Livewire\TaskTable;
use Livewire\Livewire;

it(description: 'Mount Livewire', closure: function () {
    Livewire::test(name: TaskTable::class)->assertHasNoErrors();
});
