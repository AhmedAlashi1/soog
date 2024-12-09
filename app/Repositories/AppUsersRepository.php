<?php

namespace App\Repositories;

//use Bosnadev\Repositories\Contracts\RepositoryInterface;
//use Bosnadev\Repositories\Eloquent\Repository;
use App\Repositories\Criteria\AppUserCriteria;

class AppUsersRepository
{

    public function model()
    {
        return 'App\Models\AppUsers';
    }

    public static function setType($type)
    {
        AppUserCriteria::setType($type);
    }

    public function activateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => 'active', 'activation_code' => '']);
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

    public function findRegistered()
    {
        return $this->model->pluck('device_token');
    }

}
