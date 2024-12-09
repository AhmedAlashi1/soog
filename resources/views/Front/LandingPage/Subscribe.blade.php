@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="contentSubscribe">
            <div class="container">
                <div class="sub-content-2">
                    <form action="" method="">
                        <div class="packages mb-5">
                            <h2 class="CariaBold mb-3 text-center mb-5">هل تريد مشاهدات أكثر ؟ </h2>
                            <div class="packagesItems row">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="radio" class="d-none" name="packageType" id="package1" >
                                    <label class="" for="package1">
                                        <div class="CariaBold text-center packagesItem">
                                            <h4 class="Quentity mb-3">10</h4>
                                            <h4 class="describe mb-3">
                                                أضعاف <br>
                                                المشاهدات
                                            </h4>
                                            <h4 class="price">
                                                5 <span class="CariaRegular">د.ك</span>

                                            </h4>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="radio" class="d-none" name="packageType" id="package2" >
                                    <label class="" for="package2">
                                        <div class="CariaBold text-center packagesItem">
                                            <h4 class="Quentity mb-3">25</h4>
                                            <h4 class="describe mb-3">
                                                أضعاف <br>
                                                المشاهدات
                                            </h4>
                                            <h4 class="price">
                                                10 <span class="CariaRegular">د.ك</span>
                                            </h4>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="radio" class="d-none" name="packageType" id="package3" >
                                    <label class="" for="package3">
                                        <div class="CariaBold text-center packagesItem">
                                            <h4 class="Quentity mb-3">60</h4>
                                            <h4 class="describe mb-3">
                                                أضعاف <br>
                                                المشاهدات
                                            </h4>
                                            <h4 class="price">
                                                20 <span class="CariaRegular">د.ك</span>
                                            </h4>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="FixedAdd mb-5">
                            <h2 class="CariaBold mb-3 text-center mb-5">هل تريد تثبيت أعلانك ؟ </h2>
                            <div class="FixedAddItems row">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="radio" class="d-none" name="FixedAdd" id="Fixed1" >
                                    <label class="w-100" for="Fixed1">
                                        <div class="CariaBold text-center packagesItem">
                                            <h5 class="describe CairoSemiBold mb-3">
                                                تثبيت في<br>
                                                القائمة الرئيسية
                                            </h5>
                                            <h4 class="price">
                                                5 <span class="CariaRegular">د.ك</span>
                                            </h4>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="radio" class="d-none" name="FixedAdd" id="Fixed2" >
                                    <label class="w-100" for="Fixed2">
                                        <div class="CariaBold text-center packagesItem">
                                            <h5 class="describe CairoSemiBold mb-3">
                                                تثبيت في<br>
                                                القائمة الفرعية
                                            </h5>
                                            <h4 class="price">
                                                3 <span class="CariaRegular">د.ك</span>

                                            </h4>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <input type="radio" class="d-none" name="FixedAdd" id="Fixed3" >
                                    <label class="w-100" for="Fixed3">
                                        <div class="CariaBold text-center packagesItem">
                                            <h5 class="describe CairoSemiBold mb-3">
                                                تثبيت في<br>
                                                القائمة المختصة
                                            </h5>
                                            <h4 class="price">
                                                3 <span class="CariaRegular">د.ك</span>
                                            </h4>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="PaymentWay mb-5">
                            <h2 class="CariaBold mb-3 text-center mb-5">اختر وسيلة الدفع المناسبة</h2>
                            <div class="PaymentWayItems row justify-content-lg-around">
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 md-md-0 mb-3">
                                    <input type="radio" class="d-none" name="PaymentWay" id="PaymentWay1" >
                                    <label class="w-100" for="PaymentWay1">
                                        <div class="CariaBold text-center PaymentWayItem">
                                            <img src="{{asset("assets/front/img/PaymentWay/PaymentWay5.png")}}" class="w-50">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 md-md-0 mb-3">
                                    <input type="radio" class="d-none" name="PaymentWay" id="PaymentWay2" >
                                    <label class="w-100" for="PaymentWay2">
                                        <div class="CariaBold text-center PaymentWayItem">
                                            <img src="{{asset("assets/front/img/PaymentWay/PaymentWay4.png")}}" class="w-50">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 md-md-0 mb-3">
                                    <input type="radio" class="d-none" name="PaymentWay" id="PaymentWay3" >
                                    <label class="w-100" for="PaymentWay3">
                                        <div class="CariaBold text-center PaymentWayItem">
                                            <img src="{{asset("assets/front/img/PaymentWay/PaymentWay3.png")}}" class="w-50">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 md-md-0 mb-3">
                                    <input type="radio" class="d-none" name="PaymentWay" id="PaymentWay4" >
                                    <label class="w-100" for="PaymentWay4">
                                        <div class="CariaBold text-center PaymentWayItem">
                                            <img src="{{asset("assets/front/img/PaymentWay/PaymentWay2.png")}}" class="w-50">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 md-md-0 mb-3">
                                    <input type="radio" class="d-none" name="PaymentWay" id="PaymentWay5" >
                                    <label class="w-100" for="PaymentWay5">
                                        <div class="CariaBold text-center PaymentWayItem">
                                            <img src="{{asset("assets/front/img/PaymentWay/PaymentWay1.png")}}" class="w-50">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class=" submitBtn text-center">
                            <button type="submit" class="CairoSemiBold btn">إشترك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection