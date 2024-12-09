<html>
<head>
    <meta charset="UTF-8" />
    <!-- IE Compatibility Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />

    <link href="https://fonts.googleapis.com/css?family=Scheherazade" rel="stylesheet">
    <style>
        .circle-tile-heading.orange:hover {background-color: #DA8C10;}
        .circle-tile-heading.red:hover {background-color: #CF4435;}
        /*==========  Non-Mobile First Method  ==========*/
        /* Small Devices, Tablets */
        @media only screen and (max-width : 768px) {.border-form{margin: 10px;}}
        /* Extra Small Devices, Phones */
        @media screen and (max-width : 480px) {.text-head-font{font-size: 20px;}.text-font{font-size: 18px;}.border-form{margin: 10px;}}
        /* Custom, iPhone Retina */
        @media screen and (max-width : 320px) {.text-head-font{font-size: 14px;}.text-font{font-size: 12px;}}
    </style>
</head>
<body style="direction: rtl;font-family: 'scheherazade', sans-serif;box-sizing: border-box;">
<div class="container border-form" style="margin: 10px auto;margin: 10px auto;border: 3px solid #ddd;padding-bottom: 202px;width: 500px;background-color: #f0efef;">
    <div class="row row-centered" style="text-align:center;">
        <div class="col-md-12 col-centered img-padding" style="display:inline-block;float:none;/* inline-block space fix */margin-right:-4px; padding: 35px 0 25px;">
            <img src="{{ asset('assets/mail-temp/verify2/img/head-bg.png')}}"/>
        </div>
    </div>
    <div class="clear"></div>
    <div class="row users-border row-centered" style="padding-top: 10px; text-align:center;">
        <div class="col-lg-2 col-sm-6 col-centered" style="display:inline-block;float:none;/* inline-block space fix */margin-right:-4px;">
            <div class="circle-tile " style="margin-bottom: 15px;text-align: center;margin-left: 25px;">
                <a href="#"><div class="circle-tile-heading dark-blue "style="border: 3px solid rgba(255, 255, 255, 0.3);
                border-radius: 100%;
                color: #FFFFFF;
                height: 40px;
                margin: 0 auto -40px;
                position: relative;
                transition: all 0.3s ease-in-out 0s;
                width: 40px;background-color: #34495E;bottom: 15px;"><a href="http://www.up-00.com/" target="_blank" title="http://www.up-00.com/"><img src="http://store6.up-00.com/2017-08/150175108353111.png" border="0" alt="http://store6.up-00.com/2017-08/150175108353111.png" style="line-height: 80px; padding-top:10px;"/></a></div></a>
                <div class="circle-tile-content dark-blue block-height" style="padding-top: 50px;background-color: #34495E; height: 60px; min-width: 115px;">
                    <div class="circle-tile-number text-faded " style="font-size: 22px;font-weight: 700;line-height: 1;color: rgba(255, 255, 255, 0.7);">{{array_sum($total)}}</div>
                    <div class="circle-tile-description text-faded text-bold text-head-font" style="font-size: 20px; color: rgba(255, 255, 255, 0.7);font-weight: 700;"> عدد المستخدمين</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-centered" style="display:inline-block;float:none;/* inline-block space fix */margin-right:-4px;">
            <div class="circle-tile " style="margin-bottom: 15px;text-align: center;margin-left: 25px;">
                <a href="#"><div class="circle-tile-heading orange "style="border: 3px solid rgba(255, 255, 255, 0.3);
                border-radius: 100%;
                color: #FFFFFF;
                height: 40px;
                margin: 0 auto -40px;
                position: relative;
                transition: all 0.3s ease-in-out 0s;
                width: 40px;background-color: #F39C12;bottom: 15px;"><a href="http://www.up-00.com/" target="_blank" title="http://www.up-00.com/"><img src="http://store6.up-00.com/2017-08/150175108353111.png" border="0" alt="http://store6.up-00.com/2017-08/150175108353111.png" style="line-height: 80px; padding-top:10px;"/></a></div></a>
                <div class="circle-tile-content orange block-height" style="padding-top: 50px;background-color: #F39C12; height: 60px; min-width: 115px;">
                    <div class="circle-tile-number text-faded " style="font-size: 22px;font-weight: 700;line-height: 1;color: rgba(255, 255, 255, 0.7);">{{array_sum($active)}}</div>
                    <div class="circle-tile-description text-faded text-bold text-head-font" style="font-size: 20px; color: rgba(255, 255, 255, 0.7);font-weight: 700;">  المفعلين  </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-centered" style="display:inline-block;float:none;/* inline-block space fix */margin-right:-4px;">
            <div class="circle-tile " style="margin-bottom: 15px;text-align: center;margin-left: 25px;">
                <a href="#"><div class="circle-tile-heading red "style="border: 3px solid rgba(255, 255, 255, 0.3);
                    border-radius: 100%;
                    color: #FFFFFF;
                    height: 40px;
                    margin: 0 auto -40px;
                    position: relative;
                    transition: all 0.3s ease-in-out 0s;
                    width: 40px;background-color: #d71f1f;bottom: 15px;"><a href="http://www.up-00.com/" target="_blank" title="http://www.up-00.com/"><img src="http://store6.up-00.com/2017-08/150175108353111.png" border="0" alt="http://store6.up-00.com/2017-08/150175108353111.png" style="line-height: 80px; padding-top:10px;"/></a></div></a>
                <div class="circle-tile-content red block-height" style="padding-top: 50px;background-color: #d71f1f; height: 60px; min-width: 115px;">
                    <div class="circle-tile-number text-faded " style="font-size: 22px;font-weight: 700;line-height: 1;color: rgba(255, 255, 255, 0.7);">{{array_sum($disActive)}}</div>
                    <div class="circle-tile-description text-faded text-bold text-head-font" style="font-size: 20px; color: rgba(255, 255, 255, 0.7);font-weight: 700;"> الغير مفعلين</div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="row">
            <div class="col-xs-12" style="width: 100%;padding-top: 30px;position: relative;
                    min-height: 1px;
                    float: left;text-align: center;">
                <table class="table table-responsive table-bordered" style="min-height: .01%;
                        overflow-x: auto; border: 1px solid #ddd;width: 98%;border: 1px solid #ddd;border-bottom: 3px solid #ddd;
                        max-width: 100%;
                        margin-bottom: 20px;background-color: transparent;border-spacing: 0;
                        border-collapse: collapse;margin-right: 5px; ">
                    <thead style="background-color: #f5f5f5;">
                    <tr>
                        <th class="text-right text-bold text-head-font" style="font-weight: 700; font-size: 25px; text-align: right;padding-left: 20px;">الدولة</th>
                        <th class="text-right text-bold text-head-font" style="font-weight: 700; font-size: 25px; text-align: right;">عدد المستخدمين</th>
                        <th class="text-right text-bold text-head-font" style="font-weight: 700; font-size: 25px; text-align: right;">المفعلين</th>
                        <th class="text-right text-bold text-head-font" style="font-weight: 700; font-size: 25px; text-align: right;">الغيرمفعلين</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td class="text-bold text-font" style="font-weight: 700; font-size: 20px; text-align: right;">{{$row->Country->name_ar}}</td>
                        <td class="text-bold text-font" style="font-weight: 700; font-size: 20px; text-align: right;">{{$row->total}}</td>
                        <td class="text-bold text-font" style="font-weight: 700; font-size: 20px; text-align: right;">{{$row->active}}</td>
                        <td class="text-bold text-font" style="font-weight: 700; font-size: 20px; text-align: right;">{{$row->disActive}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>



