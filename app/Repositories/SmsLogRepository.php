<?php

namespace App\Repositories;

//use Bosnadev\Repositories\Contracts\RepositoryInterface;
//use Bosnadev\Repositories\Eloquent\Repository;

class SmsLogRepository
{

    public function model()
    {
        return 'App\Models\SmsLog';
    }

    /**
     * @param array $data
     * @param string $attribute
     * @return mixed
     */
    public function deleteAll(array $data ,$attribute="id") {
        return $this->model->whereIn($attribute,$data)->delete();
    }
}
