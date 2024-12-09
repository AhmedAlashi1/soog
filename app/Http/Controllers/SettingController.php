<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings=Setting::where('set_group','general')->get();
        return view('setting.index',compact('settings'));
    }

    public function social()
    {
        $settings=Setting::where('set_group','social')->get();
        return view('setting.social',compact('settings'));
    }

    public function update(Request $request)
    {
  //        return $request->all();
        foreach ($request->except('_token') as $k => $v) {
            $this->update_setting([
                'key_id' => $k,
                'value' => $v
            ], $k);
        }
        // session()->flash('success', 'تم التعديل بنجاح ');
        return redirect()->back()->with('success', 'تم التعديل بنجاح ');
    }

    public function update_setting($data,$key){
        return Setting::where('key_id',$key)->update($data);
    }
}