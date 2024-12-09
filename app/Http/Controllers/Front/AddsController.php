<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Country;
use App\Models\Governorates;
use App\Models\Clothes;
use App\Helpers\Functions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use app\Location;



class AddsController extends Controller
{
    use Functions;
    public function create()
    {
        $this->data['countries'] = Country::get();
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
//return $this->data['categories'];

        return view("Front.LandingPage.AddAdminstermine")->with($this->data);
    }

    public function store(Request $request)
    {


        //$user = auth('api')->user();
        /*if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }*/

        if (
            empty($request->input('title')) || empty($request->input('Entitle')) ||
            empty($request->input('DescribeAd')) || empty($request->input('EnDescribeAd')) ||
            empty($request->input('price')) ||
            empty($request->input('country_id'))||
            empty($request->input('governorates_id')) ||
            empty($request->input('Pcat_id')) ||
            empty($request->input('cat_id'))

        ) {
            //dd("Here 1!");
            return redirect()->route('adds')->with('error', 'يجب تعبئة كافة الحقول المطلوبة');
        }

            $langs = ['ar', 'en'];
            $data = $request->except(['_token', 'id', 'note', 'type','use_credit','title']);
            $cat = Categories::find($request->input('cat_id'));
            $Parentcat = Categories::find($request->input('Pcat_id'));

            if (!$Parentcat) {
                //dd("Here 2!");
                return redirect()->route('adds')->with('error', 'يجب التصنيف الفرعي بشكل صحيح');
            }
            if (!$cat) {
                //dd("Here 3!");
                return redirect()->route('adds')->with('error', 'يجب التصنيف الفرعي بشكل صحيح');
            }

            if ($request->has('RoomCount'))
                $data['number_rooms'] = $request->RoomCount;
            if ($request->has('CountSwimmingPool'))
                $data['swimming_pool'] = $request->CountSwimmingPool;
            if ($request->has('CountJim'))
                $data['Jim'] = $request->CountJim;
            if ($request->has('working_condition'))
                $data['working_condition'] = $request->working_condition;
            if ($request->has('year'))
                $data['year'] = $request->year;
            if ($request->has('cere'))
                $data['cere'] = $request->cere;
            if ($request->has('number_cylinders'))
                $data['number_cylinders'] = $request->number_cylinders;
            if ($request->has('brand'))
                $data['brand_id'] = $request->brand;
            if ($request->has('salary'))
                $data['salary'] = $request->salary;
            if ($request->has('educational_level'))
                $data['educational_level_id'] = $request->educational_level;
            if ($request->has('specialization'))
                $data['specialization_id'] = $request->specialization;
            if ($request->has('biography'))
                $data['biography'] = $request->biography;
            if ($request->has('animal_type'))
                $data['animal_type_id'] = $request->animal_type;
            if ($request->has('fashion_type'))
                $data['fashion_type_id'] = $request->fashion_type;
            if ($request->has('subjects'))
                $data['subjects_id`'] = $request->subjects;


            $data['note_ar'] = $request->input('DescribeAd');
            $data['note_en'] = $request->input('EnDescribeAd');

            if(!empty($request->input('title'))){
                $data['title_ar'] = $request->title;
                $data['title_en'] = $request->Entitle;
            }

            $images_data = [];
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                        '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = 'assets/tmp';
                    $file->move($destinationPath, $fileName);
                    // create image thumb
//                $this->createThumb($destinationPath, $fileName);getCity
                    $images_data[] = ['image' => $fileName];
                }
            }


            if (count($images_data) > 0) {
                $data['image'] = $images_data[0]['image'];
            }else {
                $data['image'] = 'default.jpg';
            }

            $data['user_id'] = Auth::guard()->user()->id;

            unset($data['images']);


            $add = Clothes::create($data);
            if (!$add) {
                //dd("Here 4!");
                redirect()->route('adds')->with('error', 'فشلت عملية إضافة الإعلان !');
            }
            $add->charityImages()->createMany($images_data);


            // add payment link
            $userdata['advertisement_id']=$add->id;
            //dd("Here 5!");
            return redirect()->route('adds')->with('success', '! تم إضافة الإعلان بنجاح');


    }
    public function getCity(Request $request)
    {
        $country_id = $request->input('country');
        $gov = Governorates::where('country_id',$country_id)->get();
        return ['value' => view('Front.LandingPage.AjaxCity',compact('gov'))->render()];

    }
//    public function getCity(Request $request)
//    {
//        /*
//         * type = 1 => This is mean is Add
//         * type = 2 => This is mean is Edit
//         */
//        $Type = $request->input('Type');
//        $country_id = $request->input('country');
//        $gov = Governorates::where('country_id',$country_id)->get();
//
//        if ($Type == 1) {
//            return ['value' => view('Front.LandingPage.AjaxCity',compact(['gov','Type']))->render()];
//        }elseif ($Type == 2) {
//            $city_id = $request->input('idcity');
//            return ['value' => view('Front.LandingPage.AjaxCity',compact(['gov','city_id','Type']))->render()];
//        }else {
//            return redirect()->back();
//        }
//
//    }
    public function getSubCat(Request $request)
    {
        $category_id = $request->input('category');
        $categories = Categories::where(['status' => '1', 'parent_id' => $category_id])->get();
        $category = Categories::where(['status' => '1', 'parent_id' => $category_id])->first();
//        $category = Categories::where(['id' => $category_id])->first();

        return ['value' => view('Front.LandingPage.AjaxSubCat',compact('categories'))->render(),'data'=>$category];
    }
//    public function getSubCat(Request $request)
//    {
//        /*
//         * type = 1 => This is mean is Add
//         * type = 2 => This is mean is Edit
//         */
//        $Type = $request->input('Type');
//        $category_id = $request->input('category');
//        $categories = Categories::where(['status' => '1', 'parent_id' => $category_id])->get();
//        $category = Categories::where(['status' => '1', 'parent_id' => $category_id])->first();
//        if ($Type == 1) {
//            return ['value' => view('Front.LandingPage.AjaxSubCat',compact('categories'))->render(),'data'=>$category];
//        }elseif ($Type == 2) {
//            $idSubCat = $request->input('idSubCat');
//            return ['value' => view('Front.LandingPage.AjaxSubCatEdit',compact(['categories','idSubCat']))->render(),'data'=>$category];
//        }else {
//            return redirect()->back();
//        }
//
//
////        $category = Categories::where(['id' => $category_id])->first();
//
//    }


    public function getInputs(Request $request)
    {
        $category_id = $request->input('sub_category');
        $category = Categories::where(['id' => $category_id])->first();
        return ['value' => view('Front.LandingPage.AjaxInputs',compact('category'))->render(),'data'=>$category];
    }
//    public function getInputs(Request $request)
//    {
//        $Type = $request->input('Type');
//        $category_id = $request->input('sub_category');
//        $category = Categories::where(['id' => $category_id])->first();
//        if ($Type == 1) {
//            return [
//                'value' => view('Front.LandingPage.AjaxInputs',compact('category','Type'))->render(),
//                'data'=>$category
//            ];
//        }elseif ($Type == 2){
//            $id = $request->input('id');
//            $Product = Clothes::where(['id' => $id])->first();
//            return [
//                'value' => view('Front.LandingPage.AjaxInputs',compact('category','Type','Product'))->render(),
//                'data'=>$category
//            ];
//        }
//        else {
//            return redirect()->back();
//        }
//    }


    public function Edit($id,$idUser)
    {
        $AuthId = Auth::guard('user')->user()->id;
        $product = Clothes::with(['charityImages'])->find($id);
        if ($idUser != $AuthId || !$product) {
            return redirect()->route('user_Profile');
        }
        $this->data['countries'] = Country::get();
        $this->data['product'] = $product;
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
        $this->data['Activecategorie'] = Categories::where(['status' => '1', 'id' => $product->cat_id])->first();
        //dd("id" . $id . 'idUser' . $idUser);
        return view('Front.LandingPage.EditProduct')->with($this->data);
    }
    public function update(Request $request)
    {
        $AuthId = Auth::guard('user')->user()->id;
        $id = $request->input('ProductId');
        $product = Clothes::with(['charityImages'])->find($id);

        if ($request->input('UserId') != $AuthId || !$product || $product == null) {
            return redirect()->route('user_Profile');
        }

        $this->data['countries'] = Country::get();
        $this->data['product'] = $product;
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
        $this->data['Activecategorie'] = Categories::where(['status' => '1', 'id' => $product->cat_id])->first();
        //dd("id" . $id . 'idUser' . $idUser);
        //return view('Front.LandingPage.EditProduct')->with($this->data);


        if (
            empty($request->input('title')) || empty($request->input('Entitle')) ||
            empty($request->input('DescribeAd')) || empty($request->input('EnDescribeAd')) ||
            empty($request->input('price')) ||
            empty($request->input('country_id'))||
            empty($request->input('governorates_id')) ||
            empty($request->input('Pcat_id')) ||
            empty($request->input('cat_id'))
        ) {
            //dd("Here 1!");
            $this->data['error'] = 'يجب تعبئة كافة الحقول المطلوبة';
            redirect()->route('EditProduct',['id'=>$id,'idUser'=>$AuthId])->with($this->data);
        }

        $langs = ['ar', 'en'];
        $cat = Categories::find($request->input('cat_id'));
        $Parentcat = Categories::find($request->input('Pcat_id'));

        if (!$Parentcat) {
            //dd("Here 2!");
            $this->data['error'] = 'يجب التصنيف الفرعي بشكل صحيح';
            return redirect()->route('EditProduct',['id'=>$id,'idUser'=>$AuthId])->with($this->data);
        }
        if (!$cat) {
            //dd("Here 3!");
            $this->data['error'] = 'يجب التصنيف الفرعي بشكل صحيح';
            return redirect()->route('EditProduct',['id'=>$id,'idUser'=>$AuthId])->with($this->data);
        }

        if ($request->has('RoomCount'))
            $product->number_rooms = $request->input('RoomCount');
        if ($request->has('CountSwimmingPool'))
            $product->swimming_pool = $request->input('CountSwimmingPool');
        if ($request->has('CountJim'))
            $product->Jim = $request->input('CountJim');
        if ($request->has('working_condition'))
            $product->working_condition = $request->input('working_condition');
        if ($request->has('year'))
            $product->year = $request->input('year');
        if ($request->has('cere'))
            $product->cere = $request->input('cere');
        if ($request->has('number_cylinders'))
            $product->number_cylinders = $request->input('number_cylinders');
        if ($request->has('brand'))
            $product->brand_id = $request->input('brand');
        if ($request->has('salary'))
            $product->salary = $request->input('salary');
        if ($request->has('educational_level'))
            $product->educational_level_id = $request->input('educational_level');
        if ($request->has('specialization'))
            $product->specialization_id = $request->input('specialization');
        if ($request->has('biography'))
            $product->biography = $request->input('biography');
        if ($request->has('animal_type'))
            $product->animal_type_id = $request->input('animal_type');
        if ($request->has('fashion_type'))
            $product->fashion_type_id = $request->input('fashion_type');
        if ($request->has('subjects'))
            $product->subjects_id = $request->input('subjects');


        $product->note_ar = $request->input('DescribeAd');
        $product->note_en = $request->input('EnDescribeAd');
        $product->price = $request->input('price');
        $product->country_id = $request->input('country_id');
        $product->governorates_id = $request->input('governorates_id');
        $product->cat_id = $request->input('cat_id');
        if(!empty($request->input('title'))){
            $product->title_ar = $request->title;
            $product->title_en = $request->Entitle;
        }

        $images_data = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                    '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'assets/tmp';
                $file->move($destinationPath, $fileName);
                // create image thumb
//                $this->createThumb($destinationPath, $fileName);
                $images_data[] = ['image' => $fileName];
            }
        }

        if ($request->has('deletedImgs')) {
            $deletedImgs = $request->input('deletedImgs');
            for ($i=0;$i < count($deletedImgs);$i++) {
                $product->charityImages()->find($deletedImgs[$i])->delete();
            }
        }

        if ($product->image == "") {
            //dd(count($images_data));
            if (count($images_data) > 0) {
                $product->image = $images_data[0]['image'];
            }elseif(count($images_data) <= 0){
                $product->image = 'default.jpg';
            }
        }else {
            if (count($images_data) > 0 && $product->image == 'default.jpg') {
                $product->image = $images_data[0]['image'];
            }
        }


//        else {
//            $data['image'] = 'default.jpg';
//        }
        $dataUpdate['user_id'] = $AuthId;
        unset($dataUpdate['images']);
        //dd($data);
        $product->save($dataUpdate);
//        if (!$update) {
//            $this->data['error'] = 'فشلت عملية إضافة الإعلان !';
//            redirect()->route('EditProduct',['id'=>$id,'idUser'=>$AuthId])->with($this->data);
//        }
        $product->charityImages()->createMany($images_data);

        // add payment link
        $userdata['advertisement_id']=$product->id;
        //dd("Here 5!");
        $this->data['success'] = '! تم تعديل بيانات الإعلان بنجاح';
        return redirect()->route('EditProduct',['id'=>$id,'idUser'=>$AuthId])->with($this->data);

    }

//    function getCountry() {
//        $result = $this->getCountryFromIP();
//        //$result = Country::get();
//        return $result;
//        return view("Front.LandingPage.testcountry")->with('countryCode',$result);
//    }



    public function destroy($idAds){

        $UserId = Auth::guard()->user()->id;
        $Ads = Clothes::with(['charityImages'])->where([
            'id' => $idAds,
            'user_id' => $UserId
        ])->get();
        if($idAds == null || count($Ads) == 0  || $Ads == null){
            return redirect()->back();
        }
        $DeletedAdd = Clothes::where([
            'id' => $idAds,
            'user_id' => $UserId
        ]);
        $DeletedAdd->with(['charityImages'])->delete();
        return redirect()->route('user_Profile');
    }
}
