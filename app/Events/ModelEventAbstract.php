<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

abstract class ModelEventAbstract implements \App\Contracts\ModelEventInterface
{
    /**
     * @var Notification
     */
    protected string $notification;

    public function __construct(protected Model $model)
    {
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getNotification(): Notification
    {
        return new $this->notification($this->getModel());
    }
}
