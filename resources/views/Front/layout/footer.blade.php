<!-- New Edit -->
<div class="footer pt-lg-5 pb-lg-5 pt-4 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-lg-0 mb-3 order-lg-0 order-2 Item1">
                <h6 class="CariaBold text-white mb-4 d-lg-block d-none">حمل تطبيق تسوق مجانا</h6>
                <p class="text-center CariaBold bg-white mb-4 d-lg-block d-none SOOG"> سوق <span class="ms-3">SOOG</span></p>
                <p class="CariaRegular text-white Follow">
                    تابعنا على وسائل التواصل الاجتماعي
                    لتعرف كل جديد ومميز
                </p>
                <div class="SocialLinks">
                    <a href="#" class="text-decoration-none bg-white me-2" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="text-decoration-none bg-white me-2" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-decoration-none bg-white me-2" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-decoration-none bg-white me-2" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-lg-0 mb-0 order-lg-1 order-3 Item2">
                <h6 class="CariaBold text-white text-md-start text-center">حمل تطبيق تسوق مجانا</h6>
                <div class="ImgStores ms-lg-3 ms-0">
                    <a href="https://play.google.com/store/apps/details?id=com.ocean.soog" target="_blank"><img src="{{asset("assets/front/img/Public/GooglePlay.png")}}"></a>
                    <a href="https://apps.apple.com/us/app/soog/id6447923807" target="_blank"><img src="{{asset("assets/front/img/Public/AppleStore.png")}}"></a>
                </div>

                <div class="CopyRights CariaRegular text-white text-md-start text-center  ms-lg-3 ms-0">
                    <span>جميع الحقوق محفوظة   &copy;2023</span>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-12 mb-lg-0 ps-lg-0 ps-md-4 mb-3 order-lg-2 order-1 Item3">
                <h6 class="CariaBold text-white">خدمة الزبائن</h6>
                <div class="LinkSite CariaRegular">
                    @if(Auth::guard('user')->check())
                        <a href="{{route('adds')}}" class="d-block text-white text-decoration-none CariaRegular">
                            أضف إعلانك
                        </a>
                    @endif

{{--                    <a href="#" class="d-block text-white text-decoration-none CariaRegular">--}}
{{--                        أسئلة متكررة--}}
{{--                    </a>--}}
{{--                    <a href="#" class="d-block text-white text-decoration-none CariaRegular">--}}
{{--                        إتصل بنا--}}
{{--                    </a>--}}
                    <a href="{{route('privacy')}}" class="d-block text-white text-decoration-none CariaRegular">
                        سياسة الخصوصية
                    </a>
                    @guest('user')
                    <a class="d-block text-white text-decoration-none CariaRegular" type="button" data-bs-toggle="modal" data-bs-target="#LoginModal">
                        تسجيل الدخول
                    </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-lg-0 mb-3 order-lg-3 order-0 Item4">
                <div class="ms-lg-5 ms-0">
                    <h6 class="CariaBold text-white">القائمة البريدية</h6>
                    <p class="CariaRegular text-white txt mb-3">
                        اشترك معنا في القامة البريدية بإيميلك
                    </p>
                    <div class="d-flex align-items-center justify-content-center SearchSec">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                             viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z"/></svg>
                        <form action="" method="post" class="input-group">
                            <input class="form-control Search delEffect me-2 CariaRegular bg-none border-0" type="search"
                                   placeholder="ezaden.herzallah@gmail.com" name="Any" aria-label="Search">
                            <button class="CairoSemiBold border-1">اشتراك</button>
                        </form>
                    </div>
                    <div class="CariaRegular text-white txt txt2 mb-2">
                    <span>
                      طرق مختلفة وآمنة للدفع مع سياسة استبدال وإرجاع تضمن حقك
                      مع لقطات أنت بأمان
                    </span>
                    </div>
                    <div class="LinkPay CariaRegular">
                        <a class="text-white text-decoration-none CariaRegular">
                            <img src="{{asset("assets/front/img/Png/Pay1.png")}}">
                        </a>
                        <a class="text-white text-decoration-none CariaRegular">
                            <img src="{{asset("assets/front/img/Png/Pay2.png")}}">
                        </a>
                        <a class="text-white text-decoration-none CariaRegular">
                            <img src="{{asset("assets/front/img/Png/Pay3.png")}}">
                        </a>
                        <a class="text-white text-decoration-none CariaRegular">
                            <img src="{{asset("assets/front/img/Png/Pay4.png")}}">
                        </a>
                        <a class="text-white text-decoration-none CariaRegular">
                            <img src="{{asset("assets/front/img/Png/Pay5.png")}}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
