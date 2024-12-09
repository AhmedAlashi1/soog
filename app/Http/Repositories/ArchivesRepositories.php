<?php

namespace App\Http\Repositories;

use App\Models\SmsLog;
use Yajra\DataTables\Facades\DataTables;

class ArchivesRepositories
{

    public function getDataTableClasses(array $data)
    {
        $query = SmsLog::query();
        $skip = $data['start'] ?? 0;
        $take = $data['length'] ?? 25;
        $phone = $data['phone'] ?? null;
        if ($phone){
            $query->where('numbers' , 'like' , '%'. $phone . '%');
        }
        $count = $query->count();
        $info = $query->orderBy('id', 'desc')->skip($skip)->take($take);
        return Datatables::of($info)->setTotalRecords($count);
    }

    public function countDataTableClasses(array $data)
    {
        $query = SmsLog::query();

        return $query->count('id');
    }
}
