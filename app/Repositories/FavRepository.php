<?php

namespace App\Repositories;

//use Bosnadev\Repositories\Eloquent\Repository;
use DB;

/**
 * Class UsersRepository
 * @package App\Core\Repositories
 */
class FavRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return '\App\Models\Fav';
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
    public function deleteFav($id, $user_id)
    {
        return $this->model->where('charity_id', $id)->where('user_id', $user_id)->delete();
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
}
