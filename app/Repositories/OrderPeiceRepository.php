<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Carbon\Carbon;
use DB;
class OrderPeiceRepository extends Repository
{

    public function model()
    {
        return 'App\Models\Pieces';
    }

    public function getCountCountry($date=null,$field,$value)
    {
        $query=$this->model
            ->select(\DB::raw('count(*) as total'),
                \DB::raw('COUNT(CASE WHEN `status` = \'new\' THEN 1 END) AS new'),
                \DB::raw('COUNT(CASE WHEN `status` = \'accept\' THEN 1 END) AS accept'),
                \DB::raw('COUNT(CASE WHEN `status` = \'in_car\' THEN 1 END) AS in_car'),
                \DB::raw('COUNT(CASE WHEN `status` = \'in_landery\' THEN 1 END) AS in_landery'),
                \DB::raw('COUNT(CASE WHEN `status` = \'car_finish\' THEN 1 END) AS car_finish'),
                \DB::raw('COUNT(CASE WHEN `status` = \'complete\' THEN 1 END) AS complete')
            );
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
        $query->where($field,$value);
        return $query->first();
    }

    public function getCountUsers($user,$product,$date)
    {
        $query=$this->model->select('*')->groupBy('order_id');
        if($product){
            $query=$query->where('clothe_id','=',$product);
        }
        if($user){
            $query=$query->whereHas('clothe', function ($q) use ($user) {
                $q->where('user_id', '=', $user);
            });
        }
        if($date) {
            $daterage = explode(' - ', $date);
            $dateFrom = $daterage[0];
            $dates = explode('-', $daterage[1]);
            $dt = Carbon::create($dates[0], $dates[1], $dates[2], 0);
            $dt->toDateTimeString();
            $dt->addDay();
            $query=$query->where('created_at', '>', $dateFrom);
            $query=$query->where('created_at', '<', $dt);
        }
        $query=$query->get();
        return $query->count();
    }
    public function getCountPieces($user,$product,$date)
    {
        $query=$this->model->select('*');
        if($product){
            $query=$query->where('clothe_id','=',$product);
        }
        if($user){
            $query=$query->whereHas('clothe', function ($q) use ($user) {
                $q->where('user_id', '=', $user);
            });
        }
        if($date) {
            $daterage = explode(' - ', $date);
            $dateFrom = $daterage[0];
            $dates = explode('-', $daterage[1]);
            $dt = Carbon::create($dates[0], $dates[1], $dates[2], 0);
            $dt->toDateTimeString();
            $dt->addDay();
            $query=$query->where('created_at', '>', $dateFrom);
            $query=$query->where('created_at', '<', $dt);
        }
        return $query->sum('number');
    }
    public function getCountResets($user,$product,$date)
    {
        $query=$this->model->select('*');
        if($product){
            $query->where('clothe_id','=',$product);
        }
        if($user){
            $query->whereHas('clothe', function ($q) use ($user) {
                $q->where('user_id', '=', $user);
            });
        }
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
        return $query->sum('price');
    }

    public function getcountReport($year,$month,$day)
    {
        $query=$this->model->whereYear('created_at', '=', $year);
        $query->whereMonth('created_at', '=', $month);
        $query->whereDay('created_at', '=', $day);
        return $query->count();
    }
    public function getcountReportMonth($year,$month)
    {
        $query=$this->model->whereYear('created_at', '=', $year);
        $query->whereMonth('created_at', '=', $month);

        return $query->sum('price');
    }

}
