<?php

namespace App\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;
class UsersProviderRepository extends Repository
{

    public function model()
    {
        return 'App\Models\UsersProvider';
    }


}
