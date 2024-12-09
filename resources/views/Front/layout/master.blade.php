<!DOCTYPE html>
@if (App::getLocale() == 'en')
    <html dir="ltr">
@else
    <html dir="rtl">
@endif
<head>

    <!-- Meta Files -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="tag" content="">
    <meta name="keywords" content="">
    <!-- Meta Files -->

    <!-- Title Files -->
    <title> Strat Project </title>
    <link rel="icon" href="">
    <!-- Title Files -->
    <!-- Css Files -->
    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/bootstrap.rtl.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/all.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/fontweb.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/owl.carousel.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/owl.theme.default.min.css")}}">
    @if (App::getLocale() == 'en')
        <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/styleEn.css")}}">
        <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/mediaEn.css")}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/style.css")}}">
        <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/media.css")}}">
    @endif

    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/aos.css")}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.21/css/intlTelInput.css">
{{--    <link rel="stylesheet" type="text/css" href="{{asset("assets/front/css/intlTelInput.css")}}">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>

@include('Front.layout.header')

@yield('content')

@include('Front.layout.footer')

@guest('user')
    <!-- Modal -->
    <div class="modal fade" id="LoginValidation" tabindex="-1" aria-labelledby="LoginValidationLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0 text-start">
                    <button type="button" class="Close delEffect m-0 bg-none border-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div id="error_message"  > </div>
                    <h2 class="mb-3 CariaBold text-center"> أدخل رمز التحقق </h2>
                    <h6 class="CairoSemiBold text-center"> أرسل لك رسالة جوال الخاص بك </h6>
                    {{--                <h3 class="CariaBold text-center mt-3">00 : 00</h3>--}}
                    <form class="formPhone" id="verify-form">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" >
                        <div class="ContainerInput">
                            <input type="text" class="ConconLight Code" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" name="activation_code1"/>
                            <input type="text" class="ConconLight Code" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" name="activation_code2"/>
                            <input type="text" class="ConconLight Code" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" name="activation_code3"/>
                            <input type="text" class="ConconLight Code" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" name="activation_code4"/>
                            <div class="IsFailedSend d-flex justify-content-between align-items-center">
                                <small class="CariaLight">إذا لم تتلقى رمزًا</small>
                                <small class="CariaLight"><a href="#" class="text-dark">إعادة إرسال</a></small>
                            </div>
                        </div>
                        <button class="nav-link CairoSemiBold btnlogin border-0 bg-none mb-4" type="submit">تحقق </button>
                        <div class="text-center mb-4">
                            <label class="CairoSemiBold text-dark">
                                <input type="checkbox" name="">
                                انا اوافق على جميع <a href="PrivacyPolicy.html" class="text-decoration-none special">شروط تطبيق</a>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal Modal fade" id="LoginModal" tabindex="-1" aria-labelledby="LoginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header border-0 text-start">
                    <button type="button" class="Close delEffect m-0 bg-none border-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <h2 class="mb-3 CariaBold text-center">تسجيل دخول</h2>
                    <h6 class="CairoSemiBold text-center">اهلا بك  <span class="ms-3 special">سوق SOOG</span></h6>
                    <form class="formPhone" id="register-form">
                        @csrf
                        <label class="CariaBold">رقم الجوال</label>
                        <div class=" mb-4 CairoSemiBold d-flex">
                            <input type="tel" class="form-control delEffect phone-field" id="phoneField"
                                   name="mobile_number" >
                            <select class="selectpicker " data-live-search="true">
                                <option data-tokens="ketchup mustard" style="background-image: url('{{asset('assets/img/user.png')}}')">
                                    +992
                                </option>
                                <option data-tokens="mustard">الكويت</option>
                                <option data-tokens="frosting">الكويت</option>
                            </select>
                        </div>

                        <button class="nav-link btnlogin CairoSemiBold border-0 bg-none mb-4" type="submit"
                        >تسجيل الدخول</button>

                        <div class="text-center">
                            <label class="CairoSemiBold text-dark">
                                <input type="checkbox" name="">
                                انا اوافق على جميع <a href="{{url('privacyPolicy')}}" class="text-decoration-none special">شروط تطبيق</a>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endguest

<!-- JS Files -->

<script rel="script" type="text/javascript" src="{{asset("assets/front/js/jquery-3.5.1.min.js")}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script rel="script" type="text/javascript" src="{{asset("assets/front/js/intlTelInput-jquery.js")}}"></script>
<script rel="script" type="text/javascript" src="{{asset("assets/front/js/owl.carousel.min.js")}}"></script>
<script rel="script" type="text/javascript" src="{{asset("assets/front/js/bootstrap.min.js")}}"></script>
<script type="text/javascript" src="{{asset("assets/front/js/main.js")}}"></script>
<script type="text/javascript" src="{{asset("assets/front/js/aos.js")}}"></script>

<script>
    AOS.init();

</script>
@guest('user')
    <script>
        var input = document.querySelector("#phoneField");
        var Code = document.querySelector(".Code");
        // $("#phoneField").intlTelInput({
        //     search:true
        // });
        $(function() {
            'use strict';
            var body = $('.modal-body');
            function goToNextInput(e) {
                var key = e.which,
                    t = $(e.target),
                    sib = t.next(Code);

                // if (key != 9 && (key < 48 || key > 57) && (key < 96 && key > 105) && key != 8) {
                //     e.preventDefault();
                //     return false;
                // }

                if (key === 9) {
                    return true;
                }

                if (!sib || !sib.length) {
                    sib = body.find(Code).eq(0);
                }
                sib.select().focus();
            }
            function onKeyDown(e) {
                var key = e.which;

                // if (key === 9 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key === 8) {
                //     return true;
                // }

                //e.preventDefault();
                //return false;
                return true;
            }
            function onFocus(e) {
                $(e.target).select();
            }

            body.on('keyup', Code, goToNextInput);
            body.on('keydown', Code, onKeyDown);
            body.on('click', Code, onFocus);

        })

        $(document).ready(function() {
            $('#register-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{route('apiRegister')}}',
                    data: $('#register-form').serialize(),
                    success: function(data) {
                        $('#LoginModal').modal('hide');
                        $('#LoginValidation').modal('show');
                        $('#user_id').val(data.value);
                    },
                    error: function(data) {
                        // Handle errors
                        console.log("data" + data.request);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#verify-form').on('submit', function(e) {
                e.preventDefault();
                // let formdata = new FormData($('#verify-form')[0]);
                // console.log(formdata);
                $.ajax({
                    type: 'POST',
                    url: '{{route('apiVerify')}}',
                    data: $('#verify-form').serialize(),
                    // data: formdata,
                    success: function(data) {
                        if (data.status == 'error') {
                            $('#error_message').html("");
                            $('#error_message').addClass("alert alert-danger");
                            $('#error_message').text(data.message);
                        }else {
                            $('#LoginValidation').modal('hide');
                            // Handle successful verification
                            location.reload()
                        }

                    },

                });
            });
        });
    </script>
@endguest
@yield('js')
@yield('ScriptDotDetail')
@yield('JsProfile')
</body>
</html>
