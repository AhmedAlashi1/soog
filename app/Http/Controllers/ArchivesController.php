<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ArchivesRepositories;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class ArchivesController extends Controller
{
    public function index (){
        return view('SmsLog.index');
    }

    public function get_archives (Request $request , ArchivesRepositories $archivesRepo){
        $dataTable = $archivesRepo->getDataTableClasses($request->all());
        $dataTable->addIndexColumn();
        $dataTable->escapeColumns(['*']);
        return $dataTable->make(true);
    }

    public function delete ($id){
        $SmsLog = SmsLog::find($id);
        if ($SmsLog) {
            $SmsLog->delete();
            return response()->json([
                'message' => trans('category.property_delete_success'),
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }
}
