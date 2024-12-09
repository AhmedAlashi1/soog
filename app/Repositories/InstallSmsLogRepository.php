<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;

class InstallSmsLogRepository extends Repository
{

    public function model()
    {
        return 'App\Models\InstallSmsLog';
    }

    public function getTableData($perPage, $length, $fields)
    {
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('message_id', intval($fields['message_id']), 'eq');
        if ($fields['message']) {
            if (array_key_exists('message_operation_type', $fields)) {
                $where_obj->pushWhere('message', $fields['message'], $fields['message_operation']);
            } else {
                $where_obj->pushOrWhere('message', $fields['message'], $fields['message_operation']);
            }
        }
        if ($fields['numbers']) {
            if (array_key_exists('numbers_operation_type', $fields)) {
                $where_obj->pushWhere('numbers', $fields['numbers'], $fields['numbers_operation']);
            } else {
                $where_obj->pushOrWhere('numbers', $fields['numbers'], $fields['numbers_operation']);
            }
        }
        if ($fields['sender']) {
            if (array_key_exists('sender_operation_type', $fields)) {
                $where_obj->pushWhere('sender', $fields['sender'], $fields['sender_operation']);
            } else {
                $where_obj->pushOrWhere('sender', $fields['sender'], $fields['sender_operation']);
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

    public function getCount($id,$status)
    {
        return $this->model->where('message_id', $id)->where('status',$status)->count();
    }

}
