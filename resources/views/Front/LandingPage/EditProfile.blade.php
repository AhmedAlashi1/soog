@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="mt-4 mb-5">
            <div class="container">
{{--                {{dd(Auth::guard('user')->user())}}--}}
{{--                <div class="MyInfo mb-4">--}}
{{--                    <div class="MyInfoContainer">--}}
{{--                        <div class="MyData d-flex">--}}
{{--                            <div class="MyImg text-center position-relative">--}}
{{--                                @if(Auth::guard('user')->user()->avatar != null)--}}
{{--                                    <img src="{{asset('assets/tmp/').Auth::guard('user')->user()->avatar}}" class="w-100">--}}
{{--                                @else--}}
{{--                                    <img src="{{asset('assets/img/user.png')}}" class="w-100">--}}
{{--                                @endif--}}

{{--                            </div>--}}
{{--                            <div class="MyMainInfo mt-md-2 mt-4 ms-md-4 me-md-0 ms-2 me-2 CairoSemiBold  d-flex flex-column">--}}
{{--                                <div class="TopDiv d-flex justify-content-between mb-3">--}}
{{--                                    <div >--}}
{{--                                        <h4 class="mb-2">--}}
{{--                                            @if(Auth::guard('user')->user()->first_name == null && Auth::guard('user')->user()->last_name == null)--}}
{{--                                                {{Auth::guard('user')->user()->mobile_number}}--}}
{{--                                            @else--}}
{{--                                                {{Auth::guard('user')->user()->first_name . ' ' . Auth::guard('user')->user()->last_name}}--}}
{{--                                            @endif--}}
{{--                                        </h4>--}}
{{--                                        <div class="SocialLinks d-flex align-items-center justify-content-start">--}}
{{--                                            @if(Auth::guard('user')->user()->mobile_number)--}}
{{--                                                <a href="tel:{{Auth::guard('user')->user()->mobile_number}}"--}}
{{--                                                   class="text-decoration-none me-2" target="_blank">--}}
{{--                                                    <i class="fas fa-phone-alt ms-2"></i>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                            @if(Auth::guard('user')->user()->mobile_number != null)--}}
{{--                                                <a href="sms:{{Auth::guard('user')->user()->mobile_number}}"--}}
{{--                                                   class="text-decoration-none me-2" target="_blank" style="color: #459652;">--}}
{{--                                                    <i class="fas fa-sms"></i>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                            @if(Auth::guard('user')->user()->whats_number != null)--}}
{{--                                                <a href="https://wa.me/{{Auth::guard('user')->user()->whats_number}}" target="_blank"--}}
{{--                                                   class="text-decoration-none me-2" style="color: #459652;">--}}
{{--                                                    <i class="fab fa-whatsapp"></i>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                            @if(Auth::guard('user')->user()->email != null)--}}
{{--                                                <a href="mailto:{{Auth::guard('user')->user()->email}}" class="text-decoration-none me-2" style="color: #FF5A5A;">--}}
{{--                                                    <i class="far fa-envelope"></i>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="EditMyData mt-3">--}}
{{--                                        <a href="Edit.html" class="text-decoration-none">--}}
{{--                                            <i class="fas fa-edit me-md-2 me-0"></i>--}}
{{--                                            <span>تعديل المعلومات</span>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <p class="MyDescribe">--}}
{{--                                    {{Auth::guard('user')->user()->note}}--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="FollowData">--}}
{{--                        <div class="DataItems d-flex align-items-center justify-content-around CairoSemiBold bg-white m-auto">--}}
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
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="PostAd EditPersonalData mt-5">
                    <h6 class="CairoSemiBold mb-4">
                        <i class="fa fa-plus me-2"></i>
                        <span>معلومات أساسية </span>
                    </h6>
                    <form action="{{route('UpdateProfileData')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row w-100 m-auto">
                            <div class="ItemAd col-12 CairoSemiBold mb-4">
                                <div class="Imgs">
                                    <div class=" row" id="ImgAds">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-6 addImgsAd">
                                            <div class="StyledAddImgs p-1">
                                                <input type="file" class="form-control delEffect w-25 d-none"
                                                       id="uploadPersonImg" name="ImgUser">
                                                <label class="CustomFile text-center d-flex flex-column align-items-center justify-content-center" for="uploadPersonImg">
                                                    <i class="fal fa-image"></i>
                                                    <p class="m-0">صورتك الشخصية </p>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="ImgDisplay mt-0 col-lg-2 col-md-2 col-sm-6 col-6 d-flex" id="PersonImgDisplay">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ItemAd col-md-6 col-12 CairoSemiBold">
                                <label class="mb-2">الاسم الأول </label>
                                <input type="text" class="form-control delEffect" name="FName"
                                       placeholder="الاسم الأول" value="{{Auth::guard('user')->user()->first_name}}">
                            </div>
                            <div class="ItemAd col-md-6 col-12 CairoSemiBold">
                                <label class="mb-2">اسم العائلة</label>
                                <input type="text" class="form-control delEffect" name="SName"
                                       placeholder="اسم العائلة" value="{{Auth::guard('user')->user()->last_name}}">
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2">الايميل</label>
                                <input type="email" class="form-control text-start delEffect"
                                       name="email" placeholder="الايميل "
                                       value="{{Auth::guard('user')->user()->email}}">
                            </div>
                            <div class="ItemAd col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2">رقم الجوال</label>
                                <input type="phone" class="form-control text-start delEffect" name="phone"
                                       placeholder="رقم الجوال "
                                       value="{{Auth::guard('user')->user()->mobile_number}}">
                            </div>
{{--                            <div class="ItemAd col-md-6 col-12 CairoSemiBold mt-4">--}}
{{--                                <label class="mb-2">رقم الواتس</label>--}}
{{--                                <input type="phone" class="form-control text-start delEffect"--}}
{{--                                       name="Whatsphone" placeholder="رقم الواتس">--}}
{{--                            </div>--}}
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2"> السيرة الذاتية </label>
                                <textarea class="form-control delEffect" name="NoteUser"
                                          rows="4" placeholder="السيرة الذاتية"
                                >{{Auth::guard('user')->user()->note}}</textarea>
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <button type="submit" class="btn BtnSubmit CairoSemiBold delEffect"
                                        >حفظ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        AOS.init();
        const InputImg = document.getElementById('uploadPersonImg');
        const ParentChildsImgs = document.getElementById('PersonImgDisplay');
        let PersonIMg = null;
        InputImg.addEventListener("change", () => {
            const files = InputImg.files;
            for (let i = 0; i < files.length; i++) {
                if(files[0].type.match('image.*')) {
                    PersonIMg = files[i];
                }else if(files[i].type.match('video.*')) {
                    continue
                }
            }
            displayImages();
        })
        function displayImages() {
            let Personimages = ""
            if(PersonIMg != null) {
                Personimages += `<div class="image">
                <img src="${URL.createObjectURL(PersonIMg)}" alt="image">
                <span onclick="deleteImage(0)">&times;</span>
                </div>`;
            }else {
                Personimages = "";
            }
            ParentChildsImgs.innerHTML = Personimages;
        }
        function deleteImage(index) {
            PersonIMg = null;
            InputImg.files = null;
            displayImages();
        }
    </script>
@endsection
