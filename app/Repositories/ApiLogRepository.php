<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;

class ApiLogRepository extends Repository
{

    public function model()
    {
        return 'App\Models\ApiLog';
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
                $where_obj->where('ip_address', 'LIKE', '%' . $fields['ip_address'] . '%');
            } else {
                $where_obj->orWhere('ip_address', 'LIKE', '%' . $fields['ip_address'] . '%');
            }
        }
        if ($fields['api']) {
            if (array_key_exists('api_operation_type', $fields)) {
                $where_obj->pushWhere('api', $fields['api']);
            } else {
                $where_obj->pushOrWhere('api', $fields['api']);
            }
        }
        if ($fields['status_code']) {
            $statusCode = intval($fields['status_code']);
            if (array_key_exists('status_code_operation_type', $fields)) {
                $where_obj->pushWhere('status_code', $statusCode);
            } else {
                $where_obj->pushOrWhere('status_code', $statusCode);
            }
        }
        if ($fields['os']) {
            $statusCode = intval($fields['os']);
            if (array_key_exists('os_operation_type', $fields)) {
                $where_obj->pushWhere('os', $fields['os'], $fields['os_operation']);
            } else {
                $where_obj->pushOrWhere('os', $fields['os'], $fields['os_operation']);
            }
        }
        if ($fields['platform']) {
            if (array_key_exists('platform_operation_type', $fields)) {
                if ($fields['platform'] == 'mobile') {
                    $where_obj->pushWhere('is_mobile', true);
                } elseif ($fields['platform'] == 'desktop') {
                    $where_obj->pushWhere('is_desktop', true);
                }
            } else {
                if ($fields['platform'] == 'mobile') {
                    $where_obj->pushOrWhere('is_mobile', true);
                } elseif ($fields['platform'] == 'desktop') {
                    $where_obj->pushOrWhere('is_desktop', true);
                }
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
        $clos = ['','id', 'api', 'status_code', 'is_mobile', 'os', 'ip_address', '', 'created_at'];
        $where_obj->pushOrder($clos[$fields['order'][0]['column']], $fields['order'][0]['dir']);
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

    public function getTableDataServer($perPage, $length, $fields)
    {
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        if ($fields['ip_address']) {
            if (array_key_exists('ip_address_operation_type', $fields)) {
                $where_obj->where('ip_address', 'LIKE', '%' . $fields['ip_address'] . '%');
            } else {
                $where_obj->orWhere('ip_address', 'LIKE', '%' . $fields['ip_address'] . '%');
            }
        }
        $where_obj->pushWhereIn('api', ['sync_request_secret_key','get_backups','restore_backups']);

        if ($fields['os']) {
            $statusCode = intval($fields['os']);
            if (array_key_exists('os_operation_type', $fields)) {
                $where_obj->pushWhere('os', $fields['os'], $fields['os_operation']);
            } else {
                $where_obj->pushOrWhere('os', $fields['os'], $fields['os_operation']);
            }
        }
        if ($fields['platform']) {
            if (array_key_exists('platform_operation_type', $fields)) {
                if ($fields['platform'] == 'mobile') {
                    $where_obj->pushWhere('is_mobile', true);
                } elseif ($fields['platform'] == 'desktop') {
                    $where_obj->pushWhere('is_desktop', true);
                }
            } else {
                if ($fields['platform'] == 'mobile') {
                    $where_obj->pushOrWhere('is_mobile', true);
                } elseif ($fields['platform'] == 'desktop') {
                    $where_obj->pushOrWhere('is_desktop', true);
                }
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
        $clos = ['','id', 'api', 'status_code', 'is_mobile', 'os', 'ip_address', '', 'created_at'];
        $where_obj->pushOrder($clos[$fields['order'][0]['column']], $fields['order'][0]['dir']);
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->paginate(intval($length));
        return ['data' => $paginate->items(), 'total' => $paginate->total()];
    }

}
