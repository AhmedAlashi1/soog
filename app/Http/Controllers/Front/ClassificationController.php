<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Country;
use App\Models\Categories;
use App\Models\Clothes;
use App\Models\DeliveryTypes;
use App\Models\Fav;
use App\Models\FixedAds;
use App\Models\Item;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\Times;
use App\Repositories\TimesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class ClassificationController extends Controller
{
    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubCat($parent, $dilemeter)
    {
        $cats = Categories::where(['parent_id' => $parent])->get();

        $out = '';
        foreach ($cats as $row) {
            $out .= $row->id . $dilemeter;
            $out .= $this->getSubCat($row->id, ',');
        }
        return $out;
    }

    public function index(Request $request , string $Sub_Cat = null)
    {
        $cat_id = $request->input('cat_id');
        $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {

        }

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;


        $now = Carbon::now()->format('Y-m-d H:i:s');
        $fixed_ad = FixedAds::query();
        $fixed_ad->with('clothes.favorites', 'clothes.user', 'packages', 'cat')
            ->where('end_at', '>', $now)
            ->whereHas('packages', function ($q) {
                $q->where('type', 1);
            })
            ->where('status', '1')
            ->whereIn('home', ['2', '3'])
            ->orderBy('created_at', 'desc');


        $where_obj = Clothes::query();
        $where_obj->where('status', '1');
        $where_obj->where('confirm', '1');

        $categories = Categories::where(['status' => '1', 'parent_id' => $cat_id])->orderBy('sort_order', 'asc')->get();


        if ($request->input('cat_id')) {
            $getSub = $this->getSubCat($request->input('cat_id'), ',');
            if ($getSub) {
                $getSub = rtrim($getSub, ',');
                $cats = explode(',', $getSub);
            }
            $cats[] = intval($request->input('cat_id'));

            $where_obj->WhereIn('cat_id', $cats);


            $fixed_ad->WhereIn('cat_id', $cats);

        }
        if ($request->input('keyword')) {
            $where_obj->where(function ($query) use ($request) {
                $query->where('title_ar', 'like', '%' . $request->input('keyword') . '%')
                    ->orwhere('title_en', 'like', '%' . $request->input('keyword') . '%')
                    ->orwhere('keywords', 'like', '%' . $request->input('keyword') . '%');
            });
//            $where_obj->where('title_'.$request->header('lang'),'like','%'.$request->input('keyword').'%');
        }
        if ($request->input('order')) {
            if ($request->input('order') == 1) {
                $where_obj->orderBy('id', 'desc');
            } elseif ($request->input('order') == 2) {
                $where_obj->orderBy('id', 'asc');
            } elseif ($request->input('order') == 3) {
                $where_obj->orderBy('price', 'desc');
            } elseif ($request->input('order') == 4) {
                $where_obj->orderBy('price', 'asc');
            }
        } else {
            $where_obj->orderBy('sort_order', 'asc');
        }

        $fixed_ads = $fixed_ad->limit(9)->get(['*']);

        $paginate = $where_obj->whereHas('categories')->paginate($length);


        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        $data = ['count_total' => $paginate->total(),
            'nextPageUrl' => $paginate->nextPageUrl(),
            'pages' => ceil($paginate->total() / $length),
            'categories' => $categories,
            'fixed_ads' => $fixed_ads,


        ];
        if ($Sub_Cat != null){
            $data['Sub_Cat'] = $Sub_Cat;
        }else {
            $data['Sub_Cat'] = null;
        }
        return view("Front.LandingPage.Classification", compact('data','cat_id'));


    }

    public function filter(Request $request)
    {
        $categoryId = $request->input('categoryId');
        $mainCat = $request->input('mainCat');
        if ($mainCat){
            $categories = Categories::where(['status' => '1', 'parent_id' => $mainCat])->get();

            $categoryIds = array();

            foreach ($categories as $category) {
                $categoryIds[] = $category->id;
            }
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $fixed_ads = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                ->whereIn('cat_id', $categoryIds)
                ->whereHas('packages', function ($q) {
                    $q->where('type', 1);
                })
                ->where('status', '1')
                ->whereIn('home', ['1', '2'])
                ->orderBy('created_at', 'desc')->limit(4)->get(['*']);
            $products = Clothes::where('type', '1')->where('status', '1')->whereIn('cat_id', $categoryIds)->where('confirm', '1')->with('user', 'country', 'governorates')->orderBy('views', 'desc')->paginate(20);
            $data = [
                'products' => $products,
                'fixed_ads' => $fixed_ads,
            ];
            return ['value' => view('Front.LandingPage.categories',compact('data'))->render()];
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');
        $fixed_ads = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
            ->where('end_at', '>', $categoryId)
            ->where('cat_id', $categoryId)
            ->whereHas('packages', function ($q) {
                $q->where('type', 1);
            })
            ->where('status', '1')
            ->whereIn('home', ['1', '2'])
            ->orderBy('created_at', 'desc')->limit(4)->get(['*']);
        $products = Clothes::where('type', '1')->where('status', '1')->where('cat_id', $categoryId)->where('confirm', '1')->with('user', 'country', 'governorates')->orderBy('views', 'desc')->paginate(20);
        $data = [
            'products' => $products,
            'fixed_ads' => $fixed_ads,
        ];
        return ['value' => view('Front.LandingPage.categories',compact('data'))->render()];
    }
}
