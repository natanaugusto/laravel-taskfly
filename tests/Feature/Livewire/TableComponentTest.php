<?php
use App\Models\User;
use App\Http\Livewire\TableComponent;

use Livewire\Livewire;

test(description: 'Mount TableComponent', closure: function () {
    User::factory()->count(20)->create();
    $user = User::factory()->create();
    Livewire::actingAs($user)
        ->test(
            name: TableComponent::class,
            params: ['collection' => User::paginate()]
        )
        ->assertViewIs(name: 'livewire.table-component');
});
