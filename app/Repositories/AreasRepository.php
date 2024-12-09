<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class AreasRepository extends Repository
{

    public function model()
    {
        return 'App\Models\Area';
    }

    public function addArea($data)
    {
        try {
            $response = $this->model->create([
                'notify_email' => $data['notify_email'],
                'api' => $data['api'],
                'status' => $data['status'],
                'total_to_notify' => $data['total_to_notify'],
//                'notify_in' => $data['notify_in'],
                'multi_ip' => $data['multi_ip'],
                'send_email' => $data['send_email'],
                'is_active' => 1
            ]);
            return [true, $response];
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                return [false, 1062];
            }
            return [false, $e->getCode()];
        }
    }

}
