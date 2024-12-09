<?php

namespace App\Repositories;

//use Bosnadev\Repositories\Contracts\RepositoryInterface;
//use Bosnadev\Repositories\Eloquent\Repository;
use Carbon\Carbon;
use DB;
class NotificationRepository
{

    public function model()
    {
        return 'App\Models\Notification';
    }
}
