<?php

namespace App\Events;

use App\Mail\ModelChanges;
use App\Contracts\EventModelMailableInterface;

use Illuminate\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel implements EventModelMailableInterface
{
    public string $markdownView;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Model $model)
    {
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getMailable(): Mailable
    {
        return new ModelChanges($this->getModel(), $this->markdownView);
    }
}
