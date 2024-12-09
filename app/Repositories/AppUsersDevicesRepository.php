<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;
use Carbon\Carbon;
class AppUsersDevicesRepository extends Repository
{

    public function model()
    {
        return 'App\Models\AppUserDevice';
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function activateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => \App\Helpers\Helpers::USER_STATUS_ACTIVE, 'activation_code' => '']);
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function deactivateUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['status' => \App\Helpers\Helpers::USER_STATUS_INACTIVE]);
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function resetMobileUsers(array $ids)
    {
        return $this->model->whereIn('id', $ids)->update(['mobile_wrong_active' => 0]);
    }
    /**
     * @param $mobile
     * @param $id
     * @return mixed
     */
    public function updateMobileStatus($mobile,$id)
    {
        return $this->model->where('id','!=', $id)->where('mobile_number', $mobile)->update(['status' => \App\Helpers\Helpers::USER_STATUS_INACTIVE]);
    }
    public function updateDeviceStatus($device,$id)
    {
        return $this->model->where('id','!=', $id)->where('device_serial', $device)->update(['status' => \App\Helpers\Helpers::USER_STATUS_INACTIVE]);
    }
    /**
     * @param $ids
     * @param $type
     * @return mixed
     */
    public function getUsersForBlock($ids, $type)
    {
        return $this->model->whereIn('id', $ids)->lists($type, 'id');
    }

    /**
     * @param $year
     * @param $month
     * @param $day
     * @return mixed
     */
    public function getcount($year,$month,$day)
    {
        $query=$this->model->whereYear('created_at', '=', $year);
        $query->whereMonth('created_at', '=', $month);
        $query->whereDay('created_at', '=', $day);
        return $query->count();
    }

    public function getCities($country)
    {
        $query=$this->model
            ->where('location_country_code', '=', $country)
            ->where('city', '!=', '')
            ->groupBy('city');
        return $query->get(['city']);
    }

    public function getRegions($country,$cities)
    {
        $query=$this->model
            ->where('location_country_code', '=', $country)
            ->whereIn('city', $cities)
            ->where('locality', '!=', '')
            ->groupBy('locality');
        return $query->get(['locality']);
    }

    public function getRegionsApi($count=1000)
    {
        $query=$this->model
            ->where('is_new_region', '=', 0)
            ->where('locality', '!=', '')
            ->groupBy('locality')
            ->take($count);
        return $query->get(['locality','id','location_country_code','city']);
    }

    public function updateNewRigions($data,$ids){
        $query = $this->model->whereIn('locality',$ids)->update($data);
        return $query;
    }

    public function updateAllRigions($data){
        $query = $this->model->where('is_new_region',1)->update($data);
        return $query;
    }
    //get count
    public function get_count($request)
    {
        LengthAwarePaginator::currentPageResolver(function ()
        {
            return 1;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        //dd($request->input('city'));
        if($request->input('city')){
            $where_obj->pushWhere('city', $request->input('city'), 'eq');
        }
        if($request->input('region')){
            $where_obj->pushWhere('locality', $request->input('region'), 'eq');
        }
        if($request->input('location_country_code')){
            $where_obj->pushWhere('location_country_code', $request->input('location_country_code'), 'eq');
        }
        if($request->input('os')){
            $where_obj->pushWhere('os', $request->input('os'), 'eq');
        }
        if($request->input('sim_operator')){
            $where_obj->pushWhere('sim_operator', $request->input('sim_operator'), 'eq');
        }
        if($request->input('network_operator')){
            $where_obj->pushWhere('network_operator', $request->input('network_operator'), 'eq');
        }
        //$where_obj->pushOrder('created_at', 'desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->pushCriteria(new AdvancedSearchCriteria());
        //$paginate = $this->paginate(intval($length));
        $paginate = $this->paginate(intval(1000));
        return ['total' => $paginate->total()];
    }

    /**
     * @param $country
     * @return mixed
     */
    public function get_all($count,$city,$region,$location_country_code,$os,$sim_operator,$network_operator)
    {
        $query=$this->model
            ->take($count);
        if($city){
            $query->where('city',$city);
        }
        if($region){
            $query->where('locality',$region);
        }
        if($location_country_code){
            $query->where('location_country_code',$location_country_code);
        }
        if($os){
            $query->where('os',$os);
        }
        if($sim_operator){
            $query->where('sim_operator',$sim_operator);
        }
        if($sim_operator){
            $query->where('network_operator',$sim_operator);
        }
        return $query->get();
    }

    public function getCountCountry($date=null)
    {
        $query=$this->model
            ->select('location_country_code',\DB::raw('count(*) as total'), \DB::raw('COUNT(CASE WHEN `status` = \'active\' THEN 1 END) AS active'), \DB::raw('COUNT(CASE WHEN `status` != \'active\' THEN 1 END) AS disActive'));
        if($date) {
            $daterage = explode(' - ', $date);
            $dateFrom = $daterage[0];
            $dates = explode('-', $daterage[1]);
            $dt = Carbon::create($dates[0], $dates[1], $dates[2], 0);
            $dt->toDateTimeString();
            $dt->addDay();
            $query->where('created_at', '>', $dateFrom);
            $query->where('created_at', '<', $dt);
        }
        //->where('city', '!=', '')
        $query->groupBy('location_country_code')
            ->orderBy('total','DESC');
        return $query->get(['location_country_code']);
    }

    public function getcountReport($year,$month,$day)
    {
        $query=$this->model->whereYear('created_at', '=', $year);
        $query->whereMonth('created_at', '=', $month);
        $query->whereDay('created_at', '=', $day);
        return $query->count();
    }
}
