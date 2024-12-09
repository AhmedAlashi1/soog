<?php

namespace App\Repositories;

//use Bosnadev\Repositories\Eloquent\Repository;
use DB;

/**
 * Class UsersRepository
 * @package App\Core\Repositories
 */
class AdsRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return '\App\Models\Ads';
    }

    /**
     * @param $clos
     * @param $limit
     * @param $request
     * @return mixed
     */
    public function autoComplete($clos, $limit, $request)
    {
        return $this->model->where('name', 'like', '%' . $request . '%')->take($limit)->get($clos);
    }

    /**
     * @param array $data
     * @param string $attribute
     * @return mixed
     */
    public function deleteAll(array $data, $attribute = "id")
    {
        return $this->model->whereIn($attribute, $data)->delete();
    }



    /**
     * @param $value
     * @param $filed
     * @return mixed
     */
    public function findByField($value,$filed){
        return $this->model->where($filed,$value)->first();
    }
    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->model->count();
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function getLimitCourses($limit)
    {
        return $this->model->take($limit)->get();
    }
}
