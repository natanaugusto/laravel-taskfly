<?php

namespace App\Events;

use App\Contracts\EventModelMailableInterface;
use App\Mail\TaskChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;

abstract class BaseModel implements EventModelMailableInterface
{
    protected $mailable;

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

    public function toMailable(): Mailable
    {
        return new TaskChanged($this->getModel());
    }
}
