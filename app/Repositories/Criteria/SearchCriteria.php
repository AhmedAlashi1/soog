<?php

namespace App\Repositories\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class SearchCriteria extends Criteria
{

    protected static $where_object = null;

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->where('length', '>', 120);
        return $model;
    }

    public static function setWhereObject(array $where)
    {
        foreach ($where as $key => $value) {
            $where_obj->pushWhere($key, $value);
        }
    }

}
