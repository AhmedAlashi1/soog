<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Repositories\Criteria\AppUserCriteria;

class DriversRepository extends Repository
{

    public function model()
    {
        return 'App\Models\Drivers';
    }

    public static function setType($type)
    {
        AppUserCriteria::setType($type);
    }

    public function activateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => 'active']);
    }

    public function deactivateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => 'inactive']);
    }

    public function getcount($year,$month,$day)
    {
            $query=$this->model->whereYear('created_at', '=', $year);
            $query->whereMonth('created_at', '=', $month);
            $query->whereDay('created_at', '=', $day);
        return $query->count();
    }

    public function getCountUsers()
    {
        $query=$this->model;
        return $query->count();
    }
    public function getcountStatus($status)
    {
        $query=$this->model->where('status', '=', $status);
        return $query->count();
    }

}
