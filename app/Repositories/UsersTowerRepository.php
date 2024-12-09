<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Repositories\Criteria\AppUserCriteria;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;
use DB;
class UsersTowerRepository extends Repository
{

    public function model()
    {
        return 'App\Models\UsersTower';
    }
    /**
     * @param $type
     * @param $userId
     * @param $device
     * @param $towerId
     * @return mixed
     */

    public function addUserTower($type, $userId, $device, $towerId)
    {
        try {
            return $this->model->create([
                        'app_user_id' => $userId,
                        'tower_id' => $towerId,
                        'app_user_device_id' => $device,
                        'home_power' => 1,
                        'work_power' => 1,
            ]);
        } catch (\PDOException $ex) {
            if ($ex->errorInfo[1] == 1062) {
                return $this->model->where([
                                    'app_user_id' => $userId,
                                    'tower_id' => $towerId,
                                    'app_user_device_id' => $device,
                                ])
                                ->increment($type . '_power');
            }
        }
    }

    /**
     * @param $type
     * @param $userId
     * @return mixed
     */
    public function findMaxPower($type, $userId)
    {
        $query = $this->model->select(DB::raw('*,MAX('.$type.'_power) as max_power'));
        $query->where('app_user_device_id', $userId);
        return $query->first();
    }

    /**
     * @param $tower_id
     * @return array
     */
    public function get_count($tower_id)
    {
        LengthAwarePaginator::currentPageResolver(function ()
        {
            return 1;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        //dd($request->input('city'));
        if($tower_id){
            $where_obj->pushWhere('tower_id',$tower_id, 'eq');
        }
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->pushCriteria(new AdvancedSearchCriteria());
        //$paginate = $this->paginate(intval($length));
        $paginate = $this->paginate(intval(1000));
        return ['total' => $paginate->total()];
    }

    /**
     * @param $count
     * @return mixed
     */
    public function get_all($count,$id)
    {
        $query=$this->model
            ->take($count);
        if($id){
            $query->where('tower_id',$id);
        }
        return $query->get();
    }
}
