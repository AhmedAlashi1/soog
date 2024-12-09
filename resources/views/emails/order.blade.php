<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
</head>
<body style="direction:rtl;font-family: 'Cairo', sans-serif;margin: 0px;height: 100%">
<div>
    <div style="height:100px;background: #ffffff;color: #000000;padding-left:15px;padding-right: 15px;">
        <div style="float:right;">
            <table style="font-size: 14px;line-height: 1.2;padding-top: 5px;">
                <tr>
                    <td>أسم العميل</td>
                    <td style="padding-right: 15px;"> {{$order->user->first_name}} {{$order->user->last_name}}</td>
                </tr>
                <tr>
                    <td>رقم هاتف العميل</td>
                    <td style="padding-right: 15px;">  <?php
                        $sub = substr($order->user->mobile_number, 0, 5);
                        $number = substr($order->user->mobile_number, 5);
                        if ($sub == '00965') {
                            echo $number;
                        } else {
                            echo $order->user->mobile_number;
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>رقم الطلب</td>
                    <td style="padding-right: 15px;"> {{$order->id}}</td>
                </tr>
                <tr>
                    <td> التاريخ</td>
                    <td style="padding-right: 15px;"> {{$order->created_at}}</td>
                </tr>
                <tr>
                    <td>طريقة التوصيل</td>
                    <td style="padding-right: 15px;"> {{$order->delivery?$order->delivery->title_ar:''}}</td>
                </tr>
                <tr>
                    <td>طريقة الدفع</td>
                    <td style="padding-right: 15px;"> {{$order->payment->title_ar}}</td>
                </tr>
                <tr>
                    <td> العنوان</td>
                    @if($order->address)
                        @if($order->address->cityData)
                            <td style="padding-right: 15px;"> {{($order->address)?'('.$order->address->title.')'.'- المحافظة  '.$order->address->cityData->title_ar.'- المنطقة  '.$order->address->regionData->title_ar.'- القطعة  '.$order->address->block.'- اسم الشارع  '.$order->address->street.'- الجاده  '.$order->address->avenue.'- عماره  '.$order->address->building.'- الدور  '.$order->address->floor.'- رقم الشقة  '.$order->address->flat:''}}</td>
                        @else
                            <td style="padding-right: 15px;"> {{($order->address)?'('.$order->address->title.')'.'- المحافظة  '.$order->address->governate.'- المنطقة  '.$order->address->city.'- القطعة  '.$order->address->block.'- اسم الشارع  '.$order->address->street.'- الجاده  '.$order->address->avenue.'- عماره  '.$order->address->building.'- الدور  '.$order->address->floor.'- رقم الشقة  '.$order->address->flat:''}}</td>
                        @endif
                    @else
                        <td style="padding-right: 15px;"> {{($order->address)?'('.$order->address->title.')'.'- المحافظة  '.$order->address->governate.'- المنطقة  '.$order->address->city.'- القطعة  '.$order->address->block.'- اسم الشارع  '.$order->address->street.'- الجاده  '.$order->address->avenue.'- عماره  '.$order->address->building.'- الدور  '.$order->address->floor.'- رقم الشقة  '.$order->address->flat:''}}</td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
    <div style="clear: both"></div>
    <div>
        <h3 style="text-align: center">
            <span style="display: block; font-size: 1.5em;">أهلا بك</span>
            <span style="display: block">ملخص فاتورتك من خدمات</span>
            <span style="display: block">تطبيق {{env('APP_NAME')}}  {{env('APP_NAME_2')}}</span>
        </h3>
    </div>

    <div style="padding-left: 15px;padding-right: 15px;">
        <table style="width:100%;border: 1px solid black;border-collapse: collapse;text-align: center;font-size:14px">
            <thead>
            <tr style="border: 1px solid black;border-collapse: collapse;">
                <td style="border: 1px solid black;border-collapse: collapse;">المنتج</td>
                <td style="border: 1px solid black;border-collapse: collapse;">العدد</td>
                <td style="border: 1px solid black;border-collapse: collapse;">السعر</td>
                <td style="border: 1px solid black;border-collapse: collapse;">الاجمالى</td>

            </tr>
            </thead>
            <tbody>
            @php($i=0)
            @php($cost=0)
            @foreach($order->pieces as $kk=>$item)
                <tr style="border: 1px solid black;border-collapse: collapse;">
                    <td style="border: 1px solid black;border-collapse: collapse;">{{$item->clothe->title_ar}}</td>
                    <td style="border: 1px solid black;border-collapse: collapse;">{{$item->number}}</td>
                    <td style="border: 1px solid black;border-collapse: collapse;">{{$item->price}}</td>
                    <td style="border: 1px solid black;border-collapse: collapse;">{{number_format($item->price * $item->number,3)}}</td>
                </tr>
                @php($i +=$item->number)
                @php($cost +=($item->price*$item->number))
            @endforeach
            <tr>
                <td>إجمالى عدد القطع</td>
                <td colspan="4" style="text-align: left;direction: ltr;padding-left: 15px; "> {{$i}} </td>
            </tr>
            <tr>
                <td>تكلفة الطلب</td>
                <td colspan="4"
                    style="text-align: left;direction: ltr;padding-left: 15px; "> {{number_format($cost,3)}} </td>
            </tr>
            <tr>
                <td>تكلفة التوصيل</td>
                <td colspan="4" style="text-align: left;direction: ltr;padding-left: 15px; "> {{$order->delivery_cost?$order->delivery_cost:''}} </td>
{{--                <td colspan="4"] style="text-align: left;direction: ltr;padding-left: 15px; "> {{$order->delivery?$order->delivery->cost:''}} </td>--}}
{{--                <td colspan="4" style="text-align: left;direction: ltr;padding-left: 15px; "> {{$order->address->regionData->delivery_cost}} </td>--}}

            </tr>
            @if($order->promo)
                <tr>
                    <td>كود خصم ({{$order->promo->code}})</td>
                    @if($order->promo->type == 1)
                        <td colspan="7" style="text-align: left;direction: ltr;padding-left: 15px; ">
                            -{{$order->promo->discount}} </td>
                    @else
                        <td colspan="7" style="text-align: left;direction: ltr;padding-left: 15px; ">
                            -{{$order->promo->percent}}%
                        </td>
                    @endif
                </tr>
            @endif
            @if($order->use_credit == 1)
                <tr>
                    <td>محفظتى</td>
                    <td colspan="7" style="text-align: left;direction: ltr;padding-left: 15px; "> -{{$order->wallet_payment}} </td>
                </tr>
                <tr>
                    <td>الإجمالي</td>
                    <td colspan="4" style="text-align: left;direction: ltr;padding-left: 15px; "> {{$order->total_cost-$order->credit - $order->wallet_payment}} </td>
                </tr>
            @else
                <tr>
                    <td>الإجمالي</td>
                    <td colspan="4" style="text-align: left;direction: ltr;padding-left: 15px; "> {{$order->total_cost-$order->credit}} </td>
                </tr>
            @endif
            <tr style="border: 1px solid black;border-collapse: collapse;">
                <td style="border: 1px solid black;border-collapse: collapse;">ملاحظات</td>
                <td style="border: 1px solid black;border-collapse: collapse;" colspan="4"
                    style="text-align: left;direction: ltr;padding-left: 15px; "> {{$order->notes}} </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="clear: both"></div>
</div>
<script>
    window.print();
</script>
</body>
</html>
