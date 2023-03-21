<?php

use App\Http\Livewire\Modals\Confirm;
use Illuminate\Database\Eloquent\Model;
use Livewire\Testing\TestableLivewire;
use function Pest\Faker\faker;

const ITEM_ID = 1;

$item = new class() extends Model
{
    protected $table = 'table';

    public int $id = ITEM_ID;
};

class Component
{
    public function action(Model $item): bool
    {
        return $item->id = ITEM_ID;
    }
}

beforeEach(function () {
    $this->attrs = [
        'title' => faker()->title,
        'description' => faker()->text(),
        'confirmBtnLabel' => 'Button',
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

it(description:'modal confirmation', closure:function () use ($item) {
    $this->component->set('confirmAction', [
        Component::class,
        'action',
        $item,
        null,
    ]);
    $this->component->call('confirm')
        ->assertHasNoErrors();
});
