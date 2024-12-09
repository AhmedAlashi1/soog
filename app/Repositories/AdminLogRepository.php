<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;

class AdminLogRepository extends Repository
{

    public function model()
    {
        return 'App\Models\AdminLog';
    }

    public function getTableData($perPage, $length, $fields)
    {
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        if ($fields['ip_address']) {
            if (array_key_exists('ip_address_operation_type', $fields)) {
                $where_obj->pushWhere('ip_address', $fields['ip_address'], $fields['ip_address_operation']);
            } else {
                $where_obj->pushOrWhere('ip_address', $fields['ip_address'], $fields['ip_address_operation']);
            }
        }
        if ($fields['model']) {
            if (array_key_exists('model_operation_type', $fields)) {
                $where_obj->pushWhere('model', $fields['model']);
            } else {
                $where_obj->pushOrWhere('model', $fields['model']);
            }
        }
        if ($fields['action']) {
            if (array_key_exists('action_operation_type', $fields)) {
                $where_obj->pushWhere('action', $fields['action']);
            } else {
                $where_obj->pushOrWhere('action', $fields['action']);
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

        return ['data' => $paginate->items(), 'total' => $paginate->total()];
    }

    public function deleteAll($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

}
