<?php

namespace App\Repositories;

use App\Entities\Task;
use App\Repositories\TaskRepository;
use App\Criteria\IamCreatorCriteriaCriteria;

use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class TaskRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TaskRepositoryEloquent extends BaseRepository implements TaskRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Task::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->pushCriteria(app(IamCreatorCriteriaCriteria::class));
    }

}
