<?php

namespace App\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
class UsersCodesRepository extends Repository
{

    public function model()
    {
        return 'App\Models\UsersCodes';
    }

}
