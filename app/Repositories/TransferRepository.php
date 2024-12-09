<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Carbon\Carbon;
use DB;
class TransferRepository extends Repository
{

    public function model()
    {
        return 'App\Models\Transfer';
    }

}
