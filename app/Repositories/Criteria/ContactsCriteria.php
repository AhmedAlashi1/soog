<?php

namespace App\Repositories\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class ContactsCriteria extends Criteria
{

    public function apply($model, Repository $repository)
    {
        return $model;
    }

}
