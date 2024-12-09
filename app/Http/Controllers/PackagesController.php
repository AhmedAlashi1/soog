<?php

namespace App\Http\Controllers;


use App\Models\Packages;
use App\Models\Periodicals;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackagesController extends Controller
{
    public function index(Request $request)
    {
//        if ($request->type == '1'){
//            $packages =Packages::where('type','1')->get();
//        }else{
//            $packages =Packages::where('type','2')->get();
//        }
//        $type=$request->type;
        $packages =Packages::get();
        return view('packages.index',compact('packages'));
    }
    public function create(Request $request){
        $type=$request->type;
        return view('packages.create',compact('type'));
    }
    public function store(Request $request)
    {

//        $validated = $request->validate(Packages::$rules);


        $projects = new Packages();
        $projects->title_ar = request('title_ar');
        $projects->title_en = request('title_en');

        $projects->price = request('price');
        $projects->days = request('days');

        $projects->type =request('type');
        $projects->status = '1';
        $projects->place_installation = $request->place_installation;
        $projects->repeat_duration = $request->repeat_duration;
        $projects->number_repetitions = $request->number_repetitions;

//        if ($request->file('image') ) {
//            $name = Str::random(12);
//            $path = $request->file('image')->move('packages',
//                $name . time() . '.' . $request->file('image')->getClientOriginalExtension());
//            $projects->image= $path;
//        }
        $projects->save();
            session()->flash('Add', 'تم اضافة  الباقة بنجاح ');
            return redirect('admin/packages');
    }
    public function edit(Request $request,$id){
        $type=$request->type;


        $packages = Packages::find($id);
        return view('packages.edit',compact('type','packages','id'));
    }
    public function update(Request $request)
    {

        $id = $request->id;
//        $validated = $request->validate(Packages::$rules);
        $projects = Packages::find($id);

        $projects->title_ar = request('title_ar');
        $projects->title_en = request('title_en');
        $projects->type =request('type');

        $projects->price = request('price');
        $projects->days = request('days');
        $projects->place_installation = $request->place_installation;
        $projects->repeat_duration = $request->repeat_duration;
        $projects->number_repetitions = $request->number_repetitions;


        $projects->update();
        session()->flash('Add', 'تم تعديل الباقة بنجاح ');
        return redirect('admin/packages?type='.$request->type);
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
            $packages= Packages::find($id);

             $packages->delete();
        session()->flash('delete','تم حذف العنصر بنجاج');
        return redirect('admin/packages');

    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $coupons = Packages::find($id);
        $type=$coupons->type;
        if ($coupons->status == "1")
            $coupons->status = "0";
        else{
            $coupons->status = "1";
        }
        $coupons->update();
        session()->flash('Add','تم تعديل حالة العنصر بنجاج');
        return redirect('admin/packages?type='.$type);

    }
}
