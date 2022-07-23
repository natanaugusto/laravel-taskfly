<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TableComponent extends Component
{
    public $data;
    public $header;

    public function mount($collection): void
    {
        $this->data = $collection->getCollection()->toArray();
        $this->header = array_keys($this->data[0]);
    }

    public function render()
    {
        return view('livewire.table-component');
    }
}
