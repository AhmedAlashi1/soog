<?php

namespace App\Repositories;

use App\Models\CharityImage;
//use Bosnadev\Repositories\Contracts\RepositoryInterface;
//use Bosnadev\Repositories\Eloquent\Repository;
use Carbon\Carbon;

class ClothesRepository
{

    public function model()
    {
        return 'App\Models\Clothes';
    }
    public function deleteImage($id){
        return CharityImage::where('id',$id)->delete();
    }

    public function getHomeAds($type,$limit){
        return $this->model->where('type',$type)->where('status',1)->where('confirm',1)->orderBy('sort_order','asc')->limit($limit)->get(['*']);
    }
    public function findWehere($type){
        return $this->model->where('type','!=',$type)->get(['*']);
    }

    public function updateSort($gateId, $value)
    {
        try {
            $gate = $this->find($gateId);
            $gate->sort_order = $value;
            $gate->save();
            return true;
        } catch (\PDOException $ex) {
            return false;
        }
    }

    public function activateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => 1]);
    }

    public function deactivateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => 0]);
    }
}
