<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;

class ServiceLogRepository extends Repository
{

    public function model()
    {
        return 'App\Models\ServiceLog';
    }

    public function getTableData($perPage, $length, $fields)
    {
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        if ($fields['device_serial']) {
            if (array_key_exists('device_serial_operation_type', $fields)) {
                $where_obj->pushWhere('device_serial', $fields['device_serial'], $fields['device_serial_operation']);
            } else {
                $where_obj->pushOrWhere('device_serial', $fields['device_serial'], $fields['device_serial_operation']);
            }
        }
        if ($fields['service_code']) {
            if (array_key_exists('service_code_operation_type', $fields)) {
                $where_obj->pushWhere('service_code', $fields['service_code'], $fields['service_code_operation']);
            } else {
                $where_obj->pushOrWhere('service_code', $fields['service_code'], $fields['service_code_operation']);
            }
        }
        if ($fields['message']) {
            if (array_key_exists('message_operation_type', $fields)) {
                $where_obj->pushWhere('message', $fields['message'], $fields['message_operation']);
            } else {
                $where_obj->pushOrWhere('message', $fields['message'], $fields['message_operation']);
            }
        }
        if ($fields['number']) {
            if (array_key_exists('number_operation_type', $fields)) {
                $where_obj->pushWhere('number', $fields['number'], $fields['number_operation']);
            } else {
                $where_obj->pushOrWhere('number', $fields['number'], $fields['number_operation']);
            }
        }
        if ($fields['service_type']) {
            if (array_key_exists('service_type_operation_type', $fields)) {
                $where_obj->pushWhere('service_type', $fields['service_type']);
            } else {
                $where_obj->pushOrWhere('service_type', $fields['service_type']);
            }
        }
        if ($fields['daterange']) {
            $date = explode(' - ', $fields['daterange']);
            $dateFrom = strtotime($date[0]);
            $dateTo = strtotime($date[1]);
            if (array_key_exists('daterange_type', $fields)) {
                $where_obj->pushWhere('created_at', $dateFrom, 'gte');
                $where_obj->pushWhere('created_at', $dateTo + 24 * 3600, 'lte');
            } else {
                $where_obj->pushOrWhere('created_at', $dateFrom, 'gte');
                $where_obj->pushOrWhere('created_at', $dateTo + 24 * 3600, 'lte');
            }
        }
        $where_obj->pushOrder('created_at', 'desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->paginate(intval($length));
        $items=[];
        foreach ($paginate->items() as $key => $value) {
            $items[$key]=$value;
            $items[$key]['user'] = ($value->user)?$value->user->toArray():null;
        }
        return ['data' => $items, 'total' => $paginate->total()];
    }

    public function deleteAll($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

}
