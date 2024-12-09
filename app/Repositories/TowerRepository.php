<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Repositories\Criteria\AppUserCriteria;

class TowerRepository extends Repository
{

    public function model()
    {
        return 'App\Models\Tower';
    }

    public function findLocation($lat,$long,$distance=1){
        $query = $this->model->select('*',\DB::raw('( 6371 * acos( cos( radians('.$lat.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$long.') ) + sin( radians('.$lat.') ) * sin( radians( latitude ) ) ) ) as distance'));
        $query->having('distance','<',$distance);
        $query->orderBy('distance');
        //$query;
        return $query->get();
    }

    public function updateNewTowers($data,$ids){
        $query = $this->model->whereIn('id',$ids)->update($data);
        return $query;
    }

    public function updateAllTowers($data){
        $query = $this->model->where('is_new',1)->update($data);
        return $query;
    }
}
