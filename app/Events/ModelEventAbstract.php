<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;

abstract class ModelEventAbstract implements \App\Contracts\ModelEventInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
