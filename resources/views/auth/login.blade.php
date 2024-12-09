<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png"
        href="https://colorlib.com/etc/lf/Login_v4/images/icons/favicon.icoimages/icons/favicon.ico" />

    <link rel="stylesheet" type="text/css"
        href="https://colorlib.com/etc/lf/Login_v4/vendor/bootstrap/css/bootstrap.min.css ">

    <link rel="stylesheet" type="text/css"
        href="https://colorlib.com/etc/lf/Login_v4/fonts/font-awesome-4.7.0/css/font-awesome.min.css ">

    <link rel="stylesheet" type="text/css"
        href="https://colorlib.com/etc/lf/Login_v4/fonts/iconic/css/material-design-iconic-font.min.css">

    <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/lf/Login_v4/vendor/animate/animate.css">

    <link rel="stylesheet" type="text/css"
        href="https://colorlib.com/etc/lf/Login_v4/vendor/css-hamburgers/hamburgers.min.css">

    <link rel="stylesheet" type="text/css"
        href="https://colorlib.com/etc/lf/Login_v4/vendor/animsition/css/animsition.min.css">

    <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/lf/Login_v4/vendor/select2/select2.min.css ">

    <link rel="stylesheet" type="text/css"
        href="https://colorlib.com/etc/lf/Login_v4/vendor/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/lf/Login_v4/css/util.css">
    <link rel="stylesheet" type="text/css" href="https://colorlib.com/etc/lf/Login_v4/css/main.css">

    <meta name="robots" content="noindex, follow">
    <script>
    (function(w, d) {
        ! function(a, e, t, r, z) {
            a.zarazData = a.zarazData || {}, a.zarazData.executed = [], a.zarazData.tracks = [], a.zaraz = {
                deferred: []
            };
            var s = e.getElementsByTagName("title")[0];
            a.zarazData.c = e.cookie, s && (a.zarazData.t = e.getElementsByTagName("title")[0].text), a.zarazData
                .w = a.screen.width, a.zarazData.h = a.screen.height, a.zarazData.j = a.innerHeight, a.zarazData.e =
                a.innerWidth, a.zarazData.l = a.location.href, a.zarazData.r = e.referrer, a.zarazData.k = a.screen
                .colorDepth, a.zarazData.n = e.characterSet, a.zarazData.o = (new Date).getTimezoneOffset(), //
                a.dataLayer = a.dataLayer || [], a.zaraz.track = (e, t) => {
                    for (key in a.zarazData.tracks.push(e), t) a.zarazData["z_" + key] = t[key]
                }, a.zaraz._preSet = [], a.zaraz.set = (e, t, r) => {
                    a.zarazData["z_" + e] = t, a.zaraz._preSet.push([e, t, r])
                }, a.dataLayer.push({
                    "zaraz.start": (new Date).getTime()
                }), a.addEventListener("DOMContentLoaded", (() => {
                    var t = e.getElementsByTagName(r)[0],
                        z = e.createElement(r);
                    z.defer = !0, z.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(
                        a.zarazData))), t.parentNode.insertBefore(z, t)
                }))
        }(w, d, 0, "script");
    })(window, document);
    </script>
</head>

<body>
    <div class="limiter">
        <div class="container-login100"
            style="background-image: url(https://colorlib.com/etc/lf/Login_v4/images/bg-01.jpg);">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
                <form method="post" action="{{ route('login') }}" class="login100-form validate-form">
                    @csrf
                    <span class="login100-form-title p-b-49">
                        Login
                    </span>
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </div>
                    @endif
                    <div class="wrap-input100 validate-input m-b-23" data-validate="Email is reauired">
                        <span class="label-input100">Email</span>
                        <input type="email" name="email" class="input100 " placeholder="Type your Email">

                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <span class="label-input100">Password</span>
                        <input type="password" name="password" placeholder="Type your password"
                            class="input100  input100">

                        <span class="focus-input100" data-symbol="&#xf190;"></span>
                    </div>
                    <div class="text-right p-t-8 p-b-31">

                    </div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Login
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>

    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/jquery/jquery-3.2.1.min.js"></script>

    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/animsition/js/animsition.min.js"></script>

    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/bootstrap/js/popper.js"></script>
    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/select2/select2.min.js"></script>

    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/daterangepicker/moment.min.js"></script>
    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/daterangepicker/daterangepicker.js"></script>

    <script src="https://colorlib.com/etc/lf/Login_v4/vendor/countdowntime/countdowntime.js"></script>

    <script src="https://colorlib.com/etc/lf/Login_v4/js/main.js"></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
    </script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194"
        integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw=="
        data-cf-beacon='{"rayId":"6d1bb88c74085c5c","version":"2021.12.0","icTag":["sxg_enabled"],"token":"cd0b4b3a733644fc843ef0b185f98241","si":100}'
        crossorigin="anonymous"></script>
</body>

</html>