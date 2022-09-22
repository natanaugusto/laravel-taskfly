<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class IamCreatorCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class IamCreatorCriteriaCriteria implements CriteriaInterface
{

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where(column:'creator_id', operator:'=', value:auth()->user()->id);
        return $model;
    }
}
