<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;

abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository
{
    public function getBuilder(): Builder
    {
        $this->applyCriteria();
        $this->applyScope();
        $builder = $this->getModel();
        if (!is_a($builder, Builder::class)) {
            $builder = $builder->query();
        }
        $this->resetCriteria();
        $this->resetScope();
        $this->resetModel();
        return $builder;
    }
}
