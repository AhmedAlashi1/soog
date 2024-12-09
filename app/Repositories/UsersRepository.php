<?php

namespace App\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use DB;
/**
 * Class UsersRepository
 * @package App\Core\Repositories
 */
class UsersRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return '\App\User';
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

    public function deleteAll(array $data, $attribute = "id")
    {
        return $this->model->whereIn($attribute, $data)->delete();
    }

    public function updateStatus(array $ids, $status)
    {
        return $this->model->whereIn('id', $ids)->update(['is_active' => $status]);
    }

    public function getAllPermissions()
    {
        try {
            return \Bican\Roles\Models\Permission::all(['id', 'slug'])->toArray();
        } catch (\PDOException $ex) {
            return false;
        }
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
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateRememberToken($data,$id){
        return $this->model->where('id',$id)->update($data);
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
    public function getLimitTrainers($limit)
    {
        if(session('country')){
            return $this->model->where('type', 3)->where('country_id', session('country'))->take($limit)->get();
        }else {
            return $this->model->where('type', 3)->take($limit)->get();
        }
    }
}
