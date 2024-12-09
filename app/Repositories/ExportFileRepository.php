<?php

namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

use DB;
class ExportFileRepository extends Repository
{

    public function model()
    {
        return 'App\Models\ExportFile';
    }

    /**
     * @param $file
     * @return mixed
     */
    public function delete_file($file){
        return $this->model->where('file',$file)->delete();
    }

    public function deleteData($ids){
        return $this->model->whereIn('id',$ids)->delete();
    }
}
