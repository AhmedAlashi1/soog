@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="mt-4 mb-5">
            <div class="container">
                <div class="MyInfo mb-4">
                    <div class="MyInfoContainer">
                        <div class="MyData d-flex justify-content-between">
                            <div class="MyImg text-center position-relative">
                                @if($user->avatar != null)
                                    <img src="{{asset("assets/tmp/".$user->avatar)}}" class="w-100">
                                @else
                                    <img src="{{asset('assets/img/user.png')}}" class="w-100">
                                @endif
                            </div>
                            <div class="MyMainInfo mt-md-2 mt-4 ms-md-4 me-md-0 ms-2 me-2 CairoSemiBold  d-flex flex-column"
                                 style="flex-basis: 90%">
                                <div class="TopDiv d-flex justify-content-between mb-3 ">
                                    <div>
                                        <h4 class="mb-2">
                                            @if($user->first_name == null && $user->last_name == null)
                                                {{$user->mobile_number}}
                                            @else
                                                {{$user->first_name . ' ' . $user->last_name}}
                                            @endif
                                        </h4>
                                        <div class="SocialLinks d-flex align-items-center justify-content-start">
                                            @if(Auth::guard('user')->check())
                                                <a href="{{route('chat',$user->id)}}" class="text-decoration-none me-2"
                                                   style="color: #5C4DB1;">
                                                    <i class="fas fa-comment-alt-dots"></i>
                                                </a>
                                            @endif
                                            @if($user->mobile_number)
                                                <a href="tel:{{$user->mobile_number}}"
                                                   class="text-decoration-none me-2" target="_blank">
                                                    <i class="fas fa-phone-alt"></i>
                                                </a>
                                            @endif
                                            @if($user->mobile_number != null)
                                                <a href="sms:{{$user->mobile_number}}"
                                                   class="text-decoration-none me-2" target="_blank" style="color: #459652;">
                                                    <i class="fas fa-sms"></i>
                                                </a>
                                            @endif
                                            @if($user->whats_number != null)
                                                <a href="https://wa.me/{{$user->whats_number}}" target="_blank"
                                                   class="text-decoration-none me-2" style="color: #459652;">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            @if($user->email != null)
                                                <a href="mailto:{{$user->email}}" class="text-decoration-none me-2" style="color: #FF5A5A;">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="SellerFollow mt-3">
                                        <a href="Edit.html" class="text-decoration-none">
                                            متابعة
                                        </a>
                                    </div>
                                </div>
                                <p class="MyDescribe">
                                    {{$user->note}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="FollowData">
                        <div class="DataItems d-flex align-items-center justify-content-around CairoSemiBold bg-white m-auto">
                            <div class="Item text-center">
                                <h5>المنشورات</h5>
                                <h5>{{$count_total}}</h5>
                            </div>
                            <div class="Item text-center">
                                <h5>المتابعون</h5>
                                <h5>{{$followers}}</h5>
                            </div>
                            <div class="Item text-center">
                                <h5>يتابع</h5>
                                <h5>{{$follow}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="MyProducts MyProductsspecial">
                    <div class="row">
                        @foreach($clothes_items as $items)
                            <div class="col-lg-6 col-md-12 MyProductsItem col-sm-6 col-12">
                                <div class="ProductItem d-flex align-items-center ">
                                    <a class="ImgProduct" href="{{route("DetailProduct").'?id='.$items->id . '&cat_id='.$items->cat_id}}">
                                        <img src="{{asset('/assets/tmp/'.$items->image)}}">
                                    </a>
                                    <div class="InfoProduct mt-2 CariaRegular ms-md ms-sm-3 ms-0">
                                        <div class="nameProduct mb-2 d-flex align-items-center justify-content-between">
                                            <h6>{{$items->title_ar}}</h6>
                                            @auth('user')
                                                <i data-id="{{$items->id}}"
                                                   id="fav{{$items->id}}"
                                                   class="fav fa  @if($items->favorites->where('user_id', auth('user')->user()->id)->count() > 0)text-danger fa-heart Liked  @else fa-heart-o UnLiked  @endif fav{{$items->id}} "
                                                   style="cursor:pointer">
                                                </i>
                                            @endauth
                                        </div>
                                        <div class="describeProduct">
                                            <p>
                                               {{$items->note_ar}}
                                            </p>
                                        </div>
                                        <div class="LinksProduct d-flex align-items-center justify-content-between">
                                            <p class="m-0 text-center">
                                                {{round($items->price,0)}}
                                                @if (App::getLocale() == 'en')
                                                    {{ $items->country->coin_name_en }}
                                                @else
                                                    {{ $items->country->coin_name }}
                                                @endif
                                            </p>
                                            <div class="SocialLinks d-flex align-items-center justify-content-start">
                                                @if($items->call)
                                                    <a href="tel:{{$items->user->mobile_number}}" class="text-primary ms-1 text-decoration-none" target="_blank">
                                                        <i class="fas fa-phone-alt ms-2"></i>
                                                    </a>
                                                @endif
                                                @if($items->sms)
                                                    <a href="sms:{{$items->user->mobile_number}}" class="text-primary ms-1 text-decoration-none" target="_blank">
                                                        <i class="fas fa-sms"></i>
                                                    </a>
                                                @endif
                                                @if($items->chat && Auth::guard('user')->check())
                                                    <a class="text-decoration-none ms-1" href="{{route('chat',$items->user->id)}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.957"
                                                             height="22.274"
                                                             viewBox="0 0 32.957 32.274">
                                                            <path id="Chat"
                                                                  d="M26.718,15.183a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,26.718,15.183Zm-6.591,0a1.648,1.648,0,0,0-1.648-1.648h-.016a1.648,1.648,0,1,0,0,3.3h.016A1.648,1.648,0,0,0,20.126,15.183Zm-8.239-1.648a1.648,1.648,0,1,1,0,3.3h-.016a1.648,1.648,0,1,1,0-3.3ZM26.826,2h-16.7A30.3,30.3,0,0,0,6.4,2.135,4.9,4.9,0,0,0,3.448,3.448,4.9,4.9,0,0,0,2.135,6.4,30.3,30.3,0,0,0,2,10.131v10.1a30.3,30.3,0,0,0,.135,3.727,4.9,4.9,0,0,0,1.313,2.955A4.9,4.9,0,0,0,6.4,28.23a30.3,30.3,0,0,0,3.727.135h5.052a.886.886,0,0,1,.521.169l7.264,5.283a2.361,2.361,0,0,0,3.75-1.91V28.366a28.822,28.822,0,0,0,3.836-.135,4.5,4.5,0,0,0,4.268-4.268,30.3,30.3,0,0,0,.135-3.727v-10.1A30.3,30.3,0,0,0,34.822,6.4a4.9,4.9,0,0,0-1.313-2.955,4.9,4.9,0,0,0-2.955-1.313A30.3,30.3,0,0,0,26.826,2Z"
                                                                  transform="translate(-2 -2)" fill="#5c4db1"
                                                                  fill-rule="evenodd"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if($items->whatsApp)
                                                    <a href="https://wa.me/{{$items->user->whats_number}}" class="text-success ms-1 text-decoration-none" target="_blank">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                @endif
                                                @if($items->email)
                                                    <a href="mailto:{{$items->user->email}}" class="text-danger text-decoration-none ms-1" target="_blank">
                                                        <i class="far fa-envelope"></i>
                                                    </a>
                                                @endif
{{--                                                <a href="#" class="text-decoration-none me-2" style="color: #459652;">--}}
{{--                                                    <i class="fas fa-sms"></i>--}}
{{--                                                </a>--}}
{{--                                                <a href="Chat.html" class="text-decoration-none me-2"--}}
{{--                                                   style="color: #5C4DB1;">--}}
{{--                                                    <i class="fas fa-comment-alt-dots"></i>--}}
{{--                                                </a>--}}
{{--                                                <a href="#" class="text-decoration-none me-2" style="color: #459652;">--}}
{{--                                                    <i class="fab fa-whatsapp"></i>--}}
{{--                                                </a>--}}
{{--                                                <a href="#" class="text-decoration-none me-2" style="color: #FF5A5A;">--}}
{{--                                                    <i class="far fa-envelope"></i>--}}
{{--                                                </a>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        $('.fav'+data.clothes_id).removeClass("text-danger");
                        $('.fav'+data.clothes_id).addClass("fa-heart");
                        $('.fav'+data.clothes_id).addClass("Liked");
                        $('.fav'+data.clothes_id).addClass("text-danger");
                    }else {
                        // $('.fav'+data.clothes_id).addClass("fa-heart-o");
                        // $('.fav'+data.clothes_id).removeClass("fa-heart");
                        $('.fav'+data.clothes_id).addClass("fa-heart-o");
                        $('.fav'+data.clothes_id).addClass("UnLiked");
                        $('.fav'+data.clothes_id).removeClass("fa-heart");
                        $('.fav'+data.clothes_id).removeClass("Liked");
                        $('.fav'+data.clothes_id).removeClass("text-danger");
                    }


                }
            });
        });
    </script>
@endsection
