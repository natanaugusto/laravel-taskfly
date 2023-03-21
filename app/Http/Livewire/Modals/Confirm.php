<?php

namespace App\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;

class Confirm extends Modal
{
    public $description;

    public $icon = 'exclamation';

    public $iconColor = 'red';

    public function render(): View
    {
        return view('livewire.modals.confirm');
    }
}
