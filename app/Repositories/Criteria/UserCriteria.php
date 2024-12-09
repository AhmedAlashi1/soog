<?php

namespace App\Repositories\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class UserCriteria extends Criteria
{

    const ROLE_ADMIN_TYPE = 1;
    const ROLE_USER_TYPE = 2;

    static $type;

    public function apply($model, Repository $repository)
    {
        $model = $model->whereHas('inUserRoles', function ($query) {
            $query->where('role_id', self::$type);
        });
        return $model;
    }

    public static function setType($type)
    {
        self::$type = trim($type);
    }

}
