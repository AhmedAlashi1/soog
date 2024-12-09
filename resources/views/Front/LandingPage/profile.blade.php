@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="mt-4 mb-5">
            <div class="container">
                <div class="MyInfo mb-4">
                    <div class="MyInfoContainer">
                        <div class="MyData d-flex justify-content-between">
                            <div class="MyImg text-center position-relative">
                                @if(Auth::guard('user')->user()->avatar != null)
                                    <img src="{{asset('assets/tmp/'.Auth::guard('user')->user()->avatar)}}" class="w-100">
                                @else
                                    <img src="{{asset('assets/img/user.png')}}" class="w-100">
                                @endif
                            </div>
                            <div class="MyMainInfo mt-md-2 mt-4 ms-md-4 me-md-0 ms-2 me-2 CairoSemiBold
                            d-flex flex-column" style="flex-basis: 90%">
                                <div class="TopDiv d-flex justify-content-between mb-3">
                                    <div >
                                        <h4 class="mb-2">
                                            @if(Auth::guard('user')->user()->first_name == null && Auth::guard('user')->user()->last_name == null)
                                                {{Auth::guard('user')->user()->mobile_number}}
                                            @else
                                                {{Auth::guard('user')->user()->first_name . ' ' . Auth::guard('user')->user()->last_name}}
                                            @endif
                                        </h4>
                                        <div class="SocialLinks d-flex align-items-center justify-content-start">
                                            @if(Auth::guard('user')->user()->mobile_number)
                                                <a href="tel:{{Auth::guard('user')->user()->mobile_number}}"
                                                   class="text-decoration-none me-2" target="_blank">
                                                    <i class="fas fa-phone-alt"></i>
                                                </a>
                                            @endif
                                            @if(Auth::guard('user')->user()->mobile_number != null)
                                                <a href="sms:{{Auth::guard('user')->user()->mobile_number}}"
                                                   class="text-decoration-none me-2" target="_blank" style="color: #459652;">
                                                    <i class="fas fa-sms"></i>
                                                </a>
                                            @endif
                                            @if(Auth::guard('user')->user()->whats_number != null)
                                                <a href="https://wa.me/{{Auth::guard('user')->user()->whats_number}}" target="_blank"
                                                   class="text-decoration-none me-2" style="color: #459652;">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            @if(Auth::guard('user')->user()->email != null)
                                                <a href="mailto:{{Auth::guard('user')->user()->email}}" class="text-decoration-none me-2" style="color: #FF5A5A;">
                                                    <i class="far fa-envelope"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="EditMyData mt-3">
                                        <a href="{{route('Edit_Profile')}}" class="text-decoration-none">
                                            <i class="fas fa-edit me-md-2 me-0"></i>
                                            <span>تعديل المعلومات</span>
                                        </a>
                                    </div>
                                </div>
                                <p class="MyDescribe">
                                    {{Auth::guard('user')->user()->note}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="FollowData">
                        <div class="DataItems d-flex align-items-center justify-content-around CairoSemiBold bg-white m-auto">
{{--                            <div class="Item text-center">--}}
{{--                                <h5>المنشورات</h5>--}}
{{--                                <h5>23</h5>--}}
{{--                            </div>--}}
{{--                            <div class="Item text-center">--}}
{{--                                <h5>المتابعون</h5>--}}
{{--                                <h5>333</h5>--}}
{{--                            </div>--}}
{{--                            <div class="Item text-center">--}}
{{--                                <h5>يتابع</h5>--}}
{{--                                <h5>850</h5>--}}
{{--                            </div>--}}
                            <div class="Item text-center">
                                <h5>المنشورات</h5>
                                <h5>{{count($Products)}}</h5>
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
                        @if(count($Products) > 0)
                            @foreach($Products as $Product)
                                <div class="col-lg-6 col-md-12 MyProductsItem col-sm-6 col-12">
                                    <div class="ProductItem d-flex align-items-center ">
                                        <a class="ImgProduct" href="{{route("DetailProduct").'?id='.$Product->id . '&cat_id='.$Product->cat_id}}">
                                            <img src="{{url('/').'/assets/tmp/'.$Product->image}}">
                                        </a>
                                        <div class="InfoProduct mt-2 CariaRegular ms-md ms-sm-3 ms-0 position-relative">
                                            <div class="nameProduct mb-2 d-flex align-items-center justify-content-between">
                                                <h6>{{$Product->title_ar}}</h6>
                                                <i class="fas fa-ellipsis-h Edititems" id="Edititems"></i>
                                            </div>
                                            <div class="EditsDropDown hide position-absolute" id="EditsDropDown" data-id="{{$Product->id}}">
                                                <a class="CairoRegular d-block text-decoration-none text-dark " href="{{route("DetailProduct").'?id='.$Product->id . '&cat_id='.$Product->cat_id}}">
                                                    <i class="far fa-eye text-info me-2"></i>
                                                    <b>عرض</b>
                                                </a>
                                                <a class="CairoRegular d-block text-decoration-none text-dark" href="{{route("EditProduct",['id'=>$Product->id,'idUser' => Auth::guard('user')->user()->id])}}">
                                                    <i class="fal fa-edit text-primary me-2"></i>
                                                    <b>تعديل</b>
                                                </a>
                                                <a class="CairoRegular d-block text-decoration-none text-dark border-0 DeletedBtn"
                                                   type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal"
                                                    data-delete = '{{$Product->id}}' onclick="GetId(this)">
                                                    <i class="fas fa-trash-alt text-danger me-2"></i>
                                                    <b>حذف</b>
                                                </a>
                                            </div>
                                            <div class="describeProduct">
                                                <p>
                                                    {{$Product->note_ar}}
                                                </p>
                                            </div>
                                            <div class="LinksProduct d-flex align-items-center justify-content-between">
                                                <p class="m-0 text-center">
                                                    {{$Product->price}} د.ك
                                                </p>
                                                <div class="SocialLinks d-flex align-items-center justify-content-start">

{{--                                                    <a href="#" class="text-decoration-none me-2" style="color: #459652;" target="_blank">--}}
{{--                                                        <i class="fas fa-sms"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <a href="{{route}}" class="text-decoration-none me-2" style="color: #5C4DB1;">--}}
{{--                                                        <i class="fas fa-comment-alt-dots"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <a href="#" class="text-decoration-none me-2" style="color: #459652;" target="_blank">--}}
{{--                                                        <i class="fab fa-whatsapp"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <a href="#" class="text-decoration-none me-2" style="color: #FF5A5A;" target="_blank">--}}
{{--                                                        <i class="far fa-envelope"></i>--}}
{{--                                                    </a>--}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h5 class="CariaRegular text-danger text-center">لا يوجد منتجات !</h5>
                        @endif
{{--                            <div class="col-lg-6 col-md-12 MyProductsItem col-sm-6 col-12">--}}
{{--                                <div class="ProductItem d-flex align-items-center">--}}
{{--                                    <a class="ImgProduct" href="Product Url">--}}
{{--                                        <img src="Product Img">--}}
{{--                                    </a>--}}
{{--                                    <div class="InfoProduct mt-2 CariaRegular ms-md ms-sm-3 ms-0 position-relative">--}}
{{--                                        <div class="nameProduct  mb-2 d-flex align-items-center justify-content-between">--}}
{{--                                            <h6>Product name</h6>--}}
{{--                                            <i class="fas fa-ellipsis-h Edititems" id="Edititems"></i>--}}
{{--                                        </div>--}}
{{--                                        <div class="EditsDropDown hide position-absolute" id="EditsDropDown" data-id="">--}}
{{--                                            <a class="CairoRegular d-block text-decoration-none text-dark " type="button" data-bs-toggle="modal" data-bs-target="#ViewModal">--}}
{{--                                                <i class="far fa-eye text-info me-2"></i>--}}
{{--                                                <b>عرض</b>--}}
{{--                                            </a>--}}
{{--                                            <a class="CairoRegular d-block text-decoration-none text-dark" type="button" data-bs-toggle="modal" data-bs-target="#EditModal">--}}
{{--                                                <i class="fal fa-edit text-primary me-2"></i>--}}
{{--                                                <b>تعديل</b>--}}
{{--                                            </a>--}}
{{--                                            <a class="CairoRegular d-block text-decoration-none text-dark border-0" type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal">--}}
{{--                                                <i class="fas fa-trash-alt text-danger me-2"></i>--}}
{{--                                                <b>حذف</b>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                        <div class="describeProduct">--}}
{{--                                            <p>--}}
{{--                                                Product describe--}}
{{--                                            </p>--}}
{{--                                        </div>--}}
{{--                                        <div class="LinksProduct d-flex align-items-center justify-content-between">--}}
{{--                                            <p class="m-0 text-center">--}}
{{--                                                $Product price د.ك--}}
{{--                                            </p>--}}
{{--                                            <div class="SocialLinks d-flex align-items-center justify-content-start">--}}
{{--                                                <a href="#" class="text-decoration-none me-2" style="color: #459652;" target="_blank">--}}
{{--                                                    <i class="fas fa-sms"></i>--}}
{{--                                                </a>--}}
{{--                                                --}}{{--                                                    <a href="{{route}}" class="text-decoration-none me-2" style="color: #5C4DB1;">--}}
{{--                                                --}}{{--                                                        <i class="fas fa-comment-alt-dots"></i>--}}
{{--                                                --}}{{--                                                    </a>--}}
{{--                                                <a href="#" class="text-decoration-none me-2" style="color: #459652;" target="_blank">--}}
{{--                                                    <i class="fab fa-whatsapp"></i>--}}
{{--                                                </a>--}}
{{--                                                <a href="#" class="text-decoration-none me-2" style="color: #FF5A5A;" target="_blank">--}}
{{--                                                    <i class="far fa-envelope"></i>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title CariaRegular" >تعديل بيانات المنتج</h5>
                    <button type="button" class="btn-close delEffect text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="ViewModal" tabindex="-1" aria-labelledby="ViewModal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title CariaRegular" >تفاصيل المنتج</h5>
                    <button type="button" class="btn-close delEffect text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="DeleteModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title CariaRegular" >حذف المنتج</h5>
                    <button type="button" class="btn-close delEffect text-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="CariaRegular">هل أنت متأكد من حذف الإعلان ؟</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary CariaRegular" data-bs-dismiss="modal">إلغاء</button>
                    <a href="" class="btn btn-danger CariaRegular" id="DeleteBtn">حذف</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('JsProfile')
    <script>
        var IsShow = false;
        $(document).on('click', function (event) {
            if ($(event.target).closest('.Edititems').length) {
                IsShow = !IsShow;
                if(IsShow) {
                    $( ".EditsDropDown" ).each(function() {
                        $(this).addClass("hide");
                        $(this).removeClass("show");
                    });
                    $(event.target).closest('.ProductItem').find('.EditsDropDown').addClass('show');
                    $(event.target).closest('.ProductItem').find('.EditsDropDown').removeClass('hide');
                    IsShow = false;
                }else{
                    $( ".EditsDropDown" ).each(function() {
                        $(this).addClass("hide");
                        $(this).removeClass("show");
                    });
                }
            }else{
                IsShow = false;
                $( ".EditsDropDown" ).each(function() {
                    $(this).addClass("hide");
                    $(this).removeClass("show");
                });
            }
        });




        function GetId(btn) {
            var url = '{{route("DeleteAds", ":id") }}';
            var id = $(btn).attr('data-delete');
            url = url.replace(':id', id);
            $('#DeleteBtn').attr('href',url);
        }
    </script>
@endsection
