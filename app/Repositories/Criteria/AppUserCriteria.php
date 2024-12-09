<?php

namespace App\Repositories\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class AppUserCriteria extends Criteria
{

    const LIMITED_TYPE = 'limited';
    const FULL_TYPE = 'full';

    static $type;

    public function apply($model, Repository $repository)
    {
        $model = $model->where('type', self::$type);
        $model->orderBy('updated_at', 'desc');
        $model->with('blocked');
        return $model;
    }

    public static function setType($type)
    {
        self::$type = trim($type);
    }

}
