<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />
    <title>Ayn Jawali</title>
    <meta content='' name='description' />
    <meta content='' name='author' />
    <meta content='width=device-width, initial-scale=1.0' name='viewport' />
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Medium.eot')}}');
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Medium.eot?#iefix')}}') format('embedded-opentype'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Medium.woff')}}') format('woff'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Medium.ttf')}}') format('truetype');
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-SemiBold.eot')}}');
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-SemiBold.eot?#iefix')}}') format('embedded-opentype'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-SemiBold.woff')}}') format('woff'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-SemiBold.ttf')}}') format('truetype');
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Regular.eot')}}');
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Regular.eot?#iefix')}}') format('embedded-opentype'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Regular.woff')}}') format('woff'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Regular.ttf')}}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins.eot')}}');
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins.eot?#iefix')}}') format('embedded-opentype'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins.woff')}}') format('woff'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins.ttf')}}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Poppins';
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Light.eot')}}');
            src: url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Light.eot?#iefix')}}') format('embedded-opentype'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Light.woff')}}') format('woff'),
            url('{{ asset('assets/mail-temp/verify2/fonts/Poppins-Light.ttf')}}') format('truetype');
            font-weight: 300;
            font-style: normal;
        }
    </style>
</head>

<body style="margin-top:60px;text-align:center;background-color:#FFF;font-family:Poppins">
<div class="Content" style="height:420px;width:545px;background-color:#754bdb;margin: 0 auto;border:none;margin-bottom: 30px;border-radius:20px">
    <div class="head" style="text-align: center;padding-top: 20px;margin-bottom: 35px;">
        <img src="{{ asset('assets/mail-temp/verify2/img/head-bg.png')}}" style="width: 65px;height: 65px;border-radius: 5px;margin: 0 auto; border:2px solid #8696f4">
    </div>
    <div class="welcome">
        <h1 style="margin-bottom: 30px;font-size: 14px;color: #fff;font-weight: 300;line-height:10px;">Hi <span>{{$name}}</span>,</h1>
        <p style="width:315px;margin:0 auto;margin-bottom:30px;font-size: 14px;color:#fff;font-weight: 300;line-height:24px">We just need to verify your email address to finish updating your email.</p>
        <button style="background-color: transparent;border:solid 2px #8696f4;outline:none;width:310px;height:50px;color:#fff;border-radius:5px;font-size:16px;font-weight:600;margin-bottom: 20px;">{{$code}}</button>
        <h1 style=";font-size: 14px;color: #fff;font-weight: 300;line-height:10px;">Thanks,</h1>
        <h1 style="font-size: 14px;color: #fff;font-weight: 300;line-height:10px;">Ayn Jawali Team</h1>
    </div>
</div>
<div class="social" style="height: 50px;border-radius: 10px;border:none;width:445px;margin: 0 auto;padding: 0 50px;background-color:#f5f5f5 ;padding-top:1px;">
    <ul>
        <li style="background-color:#3f70f2;height:20px;width:20px;border-radius:5px;list-style-type:none;float:left;margin-right:15px;line-height:20px;"><a href="{{config('social.facebook')}}"><img src="{{ asset('assets/mail-temp/verify2/img/facebook.png')}}"></a></li>
        <li style="background-color:#3f70f2;height:20px;width:20px;border-radius:5px;list-style-type:none;float:left;margin-right:15px;line-height:20px;"><a href="{{config('social.twitter')}}"><img src="{{ asset('assets/mail-temp/verify2/img/twitter.png')}}"></a></li>
        <li style="background-color:#3f70f2;height:20px;width:20px;border-radius:5px;list-style-type:none;float:left;margin-right:15px;line-height:20px;"><a href="{{config('social.googleplus')}}"><img src="{{ asset('assets/mail-temp/verify2/img/gplus.png')}}"></a></li>
    </ul>
    <h1 style="font-size:12px;font-weight:600;float:right;line-height:10px;color:#c2c2c2;"><a href="http://tech-world.ws/" style="text-decoration:none;color:#c2c2c2;">tech-world.ws</a></h1>
</div>
</body>


</html>
