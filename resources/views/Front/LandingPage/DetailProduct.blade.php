@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class=" mt-4 mb-4">
            <div class="DetailsProduct">
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-6 col-12  position-relative">
                            @if(Auth::guard('user')->check())
                            <button class="LikeProduct border d-flex align-items-center justify-content-center position-absolute">
                                <i data-id="{{$repose['id']}}"  id="fav{{$repose['id']}}" class="fav fa  @if($repose->favorites->where('user_id', auth('user')->user()->id)->count() > 0)fa-heart Liked @else fa-heart-o UnLiked  @endif fav{{$repose['id']}} "  style="cursor:pointer"></i>
                            </button>
                            @endif

                            <div class="SliderImgProduct owl-carousel owl-theme" id="SliderImgProduct">
                                @foreach($repose['charityImages'] as $image)
                                    <div class="item item-worked" data-img="{{url('/').'/assets/tmp/'.$image->image}}"
                                         style="background-image: url({{url('/').'/assets/tmp/'.$image->image}});">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 mb-4">
                            <div class="TopDetail CairoSemiBold mt-3 mb-4">
                                <h2 class="TitleProduct ">
                                    {{$repose->title_ar}}
                                </h2>
                                <small>
                                    تم النشر :
                                    <span class="ms-1">{{$repose->created_at}}</span>
                                </small>
                                <p class="price mt-3 text-center">
                                    {{round($repose->price,0)}} د.ك
                                </p>
                            </div>
                            <div class="describeProduct">
                                <p class="CairoSemiBold">
                                    {{$repose->note_ar}}
                                </p>
                            </div>
                            <div class="DataContact CairoSemiBold">
                                @if($repose->call)
                                    <div class="PhoneCon">
                                        <a class="Phone text-decoration-none d-inline" href="tel:{{$repose->user->mobile_number}}">
                                            <span>{{$repose->user->mobile_number}}</span>
                                            <i class="fas fa-phone-alt ms-2"></i>
                                        </a>
                                    </div>
                                @endif

                                <div class="SocialLinks d-flex align-items-center justify-content-start">
                                    @if($repose->sms != null)
                                        <a href="sms:{{$repose->user->mobile_number}}" class="text-decoration-none me-2" style="color: #459652;" target="_blank">
                                            <i class="fas fa-sms"></i>
                                        </a>
                                    @endif
                                    @if($repose->chat && Auth::guard('user')->check())
                                        <a href="{{route('chat',$repose->user->id)}}" class="text-decoration-none me-2" style="color: #5C4DB1;">
                                            <i class="fas fa-comment-alt-dots"></i>
                                        </a>
                                    @endif
                                    @if($repose->whatsApp)
                                        <a href="https://wa.me/{{$repose->user->whats_number}}" class="text-decoration-none me-2" target="_blank" style="color: #459652;">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    @if($repose->email)
                                        <a href="mailto:{{$repose->user->email}}" class="text-decoration-none me-2" target="_blank" style="color: #FF5A5A;">
                                            <i class="far fa-envelope"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="FollowUser mt-4  mb-lg-0 mb-4">
                                    <div class="FollowCard d-flex align-items-center justify-content-between">
                                        <a class="Img-User text-decoration-none" href="{{route('seller').'?id='.$repose->user->id}}">

                                            @if($repose->user->avatar == null || empty($repose->user->avatar))
                                                <img src="{{url('/') . '/assets/img/user.png'}}">
                                            @else
                                            <img src="{{url('/') . '/assets/tmp/' . $repose->user->avatar}}">
                                            @endif

                                            @if($repose->user->first_name == null || empty($repose->user->last_name))
                                                    <span class="m-0 ms-lg-5 ms-2">{{$repose->user->mobile_number}}</span>
                                            @else
                                               <span class="m-0 ms-lg-5 ms-2">{{$repose->user->first_name}} {{$repose->user->last_name}}</span>
                                            @endif

                                        </a>
                                        <div class=" BtnFollow CairoSemiBold">
                                            <button class="btn delEffect">
                                                متابعة
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="description mb-4">
                        <div class="headerShare mb-3 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                            <h4 class="m-0">الوصف</h4>
                        </div>
                        <div class="descriptionDetails ms-auto">
                            <p class="CairoSemiBold">
                                {{$repose->note_ar}}
                            </p>
                        </div>
                    </div>

                    <div class="description">
                        <div class="headerShare mb-3 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                            <h4 class="m-0">المعلومات</h4>
                        </div>


                        <div class="descriptionDetails ms-auto">
                            <div class="row">
                                @if($repose->number_rooms != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0"> رقم الغرف</h6>
                                            <h6 class="m-0">{{$repose->number_rooms}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->swimming_pool != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">  برك سباحه</h6>
                                            <h6 class="m-0">{{$repose->swimming_pool}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->Jim != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">  صالة رياضة</h6>
                                            <h6 class="m-0">{{$repose->Jim}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->working_condition != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">حالة العمل</h6>
                                            <h6 class="m-0">@if($repose->working_condition == 1) يعمل @else لا يعمل @endif</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->year != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">السنة</h6>
                                            <h6 class="m-0">{{$repose->year}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->cere != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">القير</h6>
                                            <h6 class="m-0">@if($repose->cere == 1) أوتامتيك @else عادي @endif</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->number_cylinders != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">عدد الاسطوانات</h6>
                                            <h6 class="m-0">{{$repose->number_cylinders}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->brand != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">ماركة</h6>
                                            <h6 class="m-0">{{$repose->brand}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->salary != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">الراتب</h6>
                                            <h6 class="m-0">{{$repose->salary}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->educational_level != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">المستوى التعليمي</h6>
                                            <h6 class="m-0">{{$repose->educational_level}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->specialization != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">التخصص</h6>
                                            <h6 class="m-0">{{$repose->specialization}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->biography != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0">السيرة الشخصية</h6>
                                            <h6 class="m-0">{{$repose->biography}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->animal_type != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0"> نوع الحيوان</h6>
                                            <h6 class="m-0">{{$repose->animal_type}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->fashion_type != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0"> نوع الملابس</h6>
                                            <h6 class="m-0">{{$repose->fashion_type}}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($repose->subjects != null)
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <div class="headerShare mb-3 p-sm-4 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                            <h6 class="m-0"> الموضوع</h6>
                                            <h6 class="m-0">{{$repose->subjects}}</h6>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($repose->featured != null)
                        <div class="FeaturesAdds fixedMarg">
                            <div class="headerShare p-3  mb-3 d-flex justify-content-between align-items-center CairoSemiBold">
                                <h4 class="m-0">الأكثر المميزة</h4>
                                <div class="navgation">
                                    <a class="ms-1 bg-none border-0 text-decoration-none" href="All.html">
                                        <span class="CariaRegular me-2">عرض الجميع</span>
                                        <i class="far fa-angle-left"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="AddsItems">
                                <div class="row">
                                    <div class="owl-carousel owl-theme SliderProducts" id="SliderProducts">
                                        @foreach($repose->featured as $featured)
                                            <div class="item mb-md-3 mb-lg-0 mb-sm-4 mb-3 AddsItem">
                                                <div class="card cardShare border-0">
                                                    <a href="{{route("DetailProduct").'?id='.$featured->id . '&cat_id='.$featured->cat_id}}">
                                                        <div class="card-img card-img1 position-relative"
                                                             style="background-image: url({{url('/').'/assets/tmp/'.$featured->image}});">
                                                        </div>
                                                    </a>
                                                    <div class="card-body bg-none">
                                                        <div class="NameAdd d-flex align-items-center justify-content-between mb-2">
                                                            <p class="CairoSemiBold m-0">
                                                                {{$featured->$title}}
                                                            </p>
                                                            @auth('user')



                                                                {{--                                                        <button class="  fav "    data-id="{{$fixed_add->clothes->id}}" >--}}
                                                                <i    data-id="{{$featured->id}}"  id="fav{{$featured->id}}" class="fav fa  @if($featured->favorites->where('user_id', auth('user')->user()->id)->count() > 0)fa-heart Liked @else fa-heart-o UnLiked  @endif fav{{$featured->id}} "  style=" cursor:pointer">

                                                                </i>
                                                                {{--                                                    </button>--}}

                                                            @endauth
                                                            {{--                                                <i class="fa fa-heart Liked"></i>--}}
                                                        </div>
                                                        <div class="AddDescribe AddDescribe1 mb-2">
                                                            <p class="m-0 CariaRegular">
                                                                {{$featured->$note}}
                                                            </p>
                                                        </div>
                                                        <div class="AddDetails AddDetails1 CairoSemiBold d-flex align-items-center justify-content-between">
                            <span class="price">
                              {{$featured->price}} د.ك
                            </span>
                                                            <a href="{{route('chat',$featured->user->id)}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                                                     height="22.274"
                                                                     viewBox="0 0 32.957 32.274">
                                                                    <path id="Chat"
                                                                          d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                                          transform="translate(-2 -2)" fill="#5c4db1"
                                                                          fill-rule="evenodd"/>
                                                                </svg>
                                                            </a>
                                                            <a href="#" class="text-danger" target="_blank">
                                                                <i class="far fa-envelope"></i>
                                                            </a>
                                                            <a href="" class="text-primary" target="_blank">
                                                                <i class="fas fa-sms"></i>
                                                            </a>
                                                            <a href="" class="text-success" target="_blank">
                                                                <i class="fab fa-whatsapp"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>


@endsection
@section("ScriptDotDetail")
    <script>
        function TxtDots(){
            const section = document.getElementsByClassName('owl-dot');
            const sectionWorks = document.getElementsByClassName('item-worked');
            for(var i =0 ; i < sectionWorks.length;i++) {
                const createElement = document.createElement("img");
                var val = sectionWorks[i].getAttribute('data-img');
                createElement.src = val;
                section[i].appendChild(createElement);
            }
        }
        $(document).ready(function(){
            TxtDots();
        })
    </script>
@endsection
@section('js')


    <script>

        $(document).on('click', '.fav', function(e) {
            e.preventDefault();
            var id = $(this).data('id');


            var data = {
                "_token": "{{ csrf_token() }}",
                id: id,

            };
            // console.log(status);

            $.ajax({
                type: 'POST',
                url: '{{ route("front.fav") }}',
                data: data,
                success: function(data) {
                    if (data.message == 'add'){
                        $('.fav'+data.clothes_id).removeClass("fa-heart-o");
                        $('.fav'+data.clothes_id).removeClass("UnLiked");
                        $('.fav'+data.clothes_id).addClass("fa-heart");
                        $('.fav'+data.clothes_id).addClass("Liked");
                    }else {
                        // $('.fav'+data.clothes_id).addClass("fa-heart-o");
                        // $('.fav'+data.clothes_id).removeClass("fa-heart");
                        $('.fav'+data.clothes_id).addClass("fa-heart-o");
                        $('.fav'+data.clothes_id).addClass("UnLiked");
                        $('.fav'+data.clothes_id).removeClass("fa-heart");
                        $('.fav'+data.clothes_id).removeClass("Liked");
                    }


                }
            });
        });

    </script>
@endsection
