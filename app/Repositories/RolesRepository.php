<?php

namespace App\Repositories;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;


class RolesRepository extends Repository
{

    public function model()
    {
        return 'GeniusTS\Roles\Models\Role';
    }
}
