<?php
namespace App\Repositories;
use Bosnadev\Repositories\Eloquent\Repository;

class SettingRepository extends Repository
{
    public function model() {
        return '\App\Models\Setting';
    }

    /**
     * @param string $attribute
     * @param $data
     * @param $cols
     * @return mixed
     */
    public function findDataWhere($attribute='',$data,$cols){
        return $this->model->whereIn($attribute,$data)->get($cols);
    }

    /**
     * @param $group
     * @param array $cols
     * @return mixed
     */
    public function findBySetGroup($group, $cols = ['*'])
    {
        return $this->model->whereIn('set_group', $group)->get($cols)->pluck('value','key_id');
    }

    /**
     * @param $field
     * @param $group
     * @param array $cols
     * @return mixed
     */
    public function findWhereGroup($field,$group, $cols = ['*'])
    {
        return $this->model->whereIn($field, $group)->get($cols);
    }

    public function update_setting($data,$key){
        return $this->model->where('key_id',$key)->update($data);
    }
}