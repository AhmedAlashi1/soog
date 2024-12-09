<?php

namespace App\Repositories;

//use Bosnadev\Repositories\Contracts\RepositoryInterface;
//use Bosnadev\Repositories\Eloquent\Repository;

//use Illuminate\Config\Repository;

class SmsGatesRepository
{

    public function model()
    {
        return 'App\Models\SmsGate';
    }

    public function getNextGate($gate_sort = 0)
    {
        return $this->model->where('sort_order', '>', $gate_sort)
                        ->orderBy('sort_order', 'asc')
                        ->first();
    }

    public function updateSort($gateId, $value)
    {
        try {
            $gate = $this->find($gateId);
            if (!$gate) {
                return false;
            }
            $newGateSort = intval($gate->sort_order) + intval($value);
            $updateGate = $this->findBy('sort_order', $newGateSort);
            if (!$updateGate) {
                return true;
            }
            $updateGate->sort_order -= $value;
            $updateGate->save();
            $gate->sort_order = $newGateSort;
            $gate->save();
            return true;
        } catch (\PDOException $ex) {
            return false;
        }
    }

    public function addGate($data)
    {
        try {
            $lastGate = $this->model->orderBy('sort_order', 'desc')->limit(1)->first();
            $data['sort_order'] = intval($lastGate->sort_order) + 1;
            return $this->create($data);
        } catch (\PDOException $ex) {
            return false;
        }
    }

}
