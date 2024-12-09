@extends('layouts.master')
@section('css')

    @section('title')
        المستخدمين
    @stop

    <!-- Internal Data table css -->

    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet"/>

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('orders.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                {{ trans('orders.page_title') }}</span>
            </div>

        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div id="error_message"></div>
    <div class="modal" id="modaldemo8" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('admins.dele') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p>{{ trans('admins.aresure') }}</p><br>
                    <input class="form-control" name="usernamed" id="usernamed" type="text" readonly="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('admins.close') }}</button>
                    <button type="submit" class="btn btn-danger" id="dletet">{{ trans('admins.save') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modaldemo100" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('admins.dele') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p>{{ trans('admins.aresure') }}</p><br>
                    <input class="form-control" name="usernamed" id="usernamed" type="text" readonly=""
                           value="{{ trans('orders.Delete_all') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('admins.close') }}</button>
                    <button type="submit" class="btn btn-danger" id="dletet100">{{ trans('admins.save') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="note" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('admins.dele') }}</h6>
                </div>
                <form id="formnote">
                    <div class="form-group col-md-12">
                        <label for="notes">{{ trans('orders.note') }} :</label>
                        <input type="text" class="form-control" id="notes" name="notes" required>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success Addnoteww">{{ trans('category.Save') }}</button>
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ trans('category.Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="payment/update" method="post" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="patch">
                        <input type="hidden" name="_token" value="o2RMVxMUvjafmzNYvClExYr7nxek043oY40uUqoM">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <table class="table table-hover" id="example1" data-page-length="50"
                                   style=" text-align: center;">
                                <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>الوصف</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <td id="Numberorders"></td>
                                </tr>
                                <tr>
                                    <th>الاجمالي</th>
                                    <td id="Totalinvoices"></td>
                                </tr>
                                <tr>
                                    <th>عدد طلبات العميل</th>
                                    <td id="pieces_count"></td>
                                </tr>
                                <tr>
                                    <th>وقت الطلب</th>
                                    <td id="date"></td>
                                </tr>
                                <tr>
                                    <th>طريقة الدفع</th>
                                    <td id="Payment"></td>
                                </tr>
                                <tr>
                                    <th>الدولة</th>
                                    <td id="country_name"></td>
                                </tr>
                                <tr>
                                    <th>المنطقة</th>
                                    <td id="Governorate"></td>
                                </tr>
                                <tr>
                                    <th>وقت التوصيل</th>
                                    <td id="Delivery_time"></td>
                                </tr>
                                <tr>
                                    <th>رقم العميل</th>
                                    <td id="Customer_Number"></td>
                                </tr>
                                <tr>
                                    <th>اسم العميل</th>
                                    <td id="Name"></td>
                                </tr>
                                <tr>
                                    <th>رقم الجوال</th>
                                    <td id="Mobile_number"></td>
                                </tr>
                                <tr>
                                    <th>اجمالي فواتير العميل</th>
                                    <td id="Total"></td>
                                </tr>
                                <tr>
                                    <th>القيمة المرجعة من المحفظة</th>
                                    <td id="credit_userds"></td>
                                </tr>
                                <tr>
                                    <th>نوع الجهاز</th>
                                    <td id="Device_type"></td>
                                </tr>
                                <tr>
                                    <th>ملاحظات</th>
                                    <td id="Notes"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-secondary" target="_blank" id="PDFFF">PDF</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showProdects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" autocomplete="off" enctype="multipart/form-data">
                        <div class="row">
                            <table class="table table-hover" id="example1" data-page-length="50"
                                   style=" text-align: center;">
                                <thead>
                                <tr>
                                    <th>{{ trans('clothes.Prodect') }}</th>
                                    <th>{{ trans('clothes.Price') }}</th>
                                    <th>{{ trans('clothes.Quntaty') }}</th>
                                </tr>
                                </thead>
                                <tbody id="showprod">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @can('order-view')
        <div class="row row-sm">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('orders.export') }}" method="get">
                            @csrf
                            <div class="row mg-b-20">
                                <div class="parsley-input col-md-3" id="fnWrapper">
                                    <label>{{trans('orders.status')}} :</label>
                                    <select class="form-control form-control-md mg-b-20" name="status"
                                            id="statusd">
                                        <option value="">{{ trans('orders.all') }}</option>
                                        <option value="new">{{ trans('orders.new') }}</option>
                                        <option value="pay_pending">{{ trans('orders.pay_pending') }}</option>
                                        <option
                                                value="shipping_complete">{{ trans('orders.shipping_complete') }}</option>
                                        <option value="shipping">{{ trans('orders.shipping') }}</option>
                                        <option value="complete">{{ trans('orders.complete') }}</option>
                                    </select>
                                </div>
                                <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                    <label>{{trans('orders.payment_method')}} :</label>
                                    <select class="form-control form-control-md mg-b-20" name="payment_method"
                                            id="payment_methodd">
                                        <option value="">{{ trans('orders.all') }}</option>
                                        @foreach($payment as $payment)
                                            @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                                <option value="{{ $payment->id }}">{{ $payment->title_en }}</option>
                                            @else
                                                <option value="{{ $payment->id }}">{{ $payment->title_ar }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="parsley-input col-md-3" id="fnWrapper">
                                    <label for="exampleInputEmail1">{{ trans('app_users.name') }} :</label>
                                    <input type="text" class="form-control" id="named" name="name">
                                </div>
                                <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                    <label>{{trans('orders.phone')}} :</label>
                                    <input type="number" class="form-control form-control-md mg-b-20"
                                           name="phone"
                                           id="phoned">
                                </div>
                                <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                    <label>{{trans('orders.payment_status')}} :</label>
                                    <select class="form-control form-control-md mg-b-20" id="payment_statusd"
                                            name="payment_status">
                                        <option value="">{{ trans('orders.all') }}</option>
                                        <option value="1">تم الدفع</option>
                                        <option value="2">معلق للدفع</option>
                                        <option value="3">فشل الدفع</option>
                                    </select>
                                </div>
                                <div class="parsley-input col-md-3" id="fnWrapper">
                                    <label for="exampleInputEmail1">{{ trans('orders.num') }} :</label>
                                    <input type="number" class="form-control" id="id_orderd" name="id_orderd">
                                </div>

                                <div class="parsley-input col-md-3" id="fnWrapper">
                                    <label for="exampleInputEmail1">من تاريخ الطلب :</label>

                                  <input class="form-control" id="created_at" name="created_at" placeholder="MM/DD/YYYY" type="date">
                                </div>
                                <div class="parsley-input col-md-3" id="fnWrapper">
                                    <label for="exampleInputEmail1">الى تاريخ الطلب :</label>

                                    <input class="form-control" id="created_at_to" name="created_at_to" placeholder="MM/DD/YYYY" type="date">
                                </div>
                                <div class="parsley-input col-md-3" id="fnWrapper">
                                    <label for="exampleInputEmail1">{{ trans('app_users.country') }} :</label>
                                    <input type="text" class="form-control" id="Countryd" name="Country">
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary" id="s">{{ trans('orders.Sarech') }}</button>
                            @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                <button type="submit" class="btn btn-success float-right">Excel</button>
                            @else
                                <button type="submit" class="btn btn-success float-left">Excel</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-xl-12">
            <div class="card-header">
                <button class="btn btn-danger" id="Delete_all"><i
                            class="las la-clipboard"> {{ trans('orders.Delete_all') }} </i></button>
                <button class="btn btn-secondary" id="Request_Accept"><i
                            class="las la-clipboard">  {{ trans('orders.Request_Accept') }}  </i>
                </button>
                <button class="btn btn-info" id="Order_processing"><i
                            class="las la-clipboard">{{ trans('orders.Order_processing') }}</i>
                </button>
                <button class="btn btn-purple" id="Cancelling_order"><i
                            class="las la-clipboard"> {{ trans('orders.cancel_order') }}  </i>
                </button>
                <button class="btn btn-success-gradient" id="Charged"><i
                            class="las la-clipboard"> {{ trans('orders.Charged') }}</i></button>
                <button class="btn btn-success" id="Receipt_confirmed"><i class="las la-clipboard">
                        {{ trans('orders.Receipt_confirmed') }}</i></button>
                <button class="btn btn-success" id="print_orders"><i class="las la-clipboard">
                        {{ trans('orders.print') }}</i></button>
            </div>
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        @can('order-view')
                            <table class="table table-hover" id="get_categories" style=" text-align: center;">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{ trans('orders.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('orders.Governorate') }}</th>
                                    <th class="border-bottom-0">{{ trans('app_users.country') }}</th>
                                    <th class="border-bottom-0">{{ trans('orders.sum') }}</th>
                                    <th class="border-bottom-0">{{ trans('orders.status') }}</th>
                                    <th class="border-bottom-0">{{ trans('orders.payment') }}</th>
                                    <th class="border-bottom-0">{{ trans('orders.total') }}</th>
                                    <th class="border-bottom-0">{{ trans('orders.notify') }}</th>
                                    <th class="border-bottom-0">
                                        @canany([ 'order-view' , 'order-delete' ])
                                            {{ trans('category.Processes') }}
                                        @endcanany
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <!-- Internal Select2.min js -->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>
    <!-- Internal Nice-select js-->
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>
    <script>
        var local = "{{ App::getLocale() }}";
        $('#s').click(function (e) {
            e.preventDefault();
            var name = $('#named').val();
            var phone = $('#phoned').val();
            var payment_method = $('#payment_methodd').val();
            var payment_status = $('#payment_statusd').val();
            var status = $('#statusd').val();
            var id_order = $('#id_orderd').val();
            var created_at = $('#created_at').val();
            var created_at_to = $('#created_at_to').val();
            var Countryd = $('#Countryd').val();
            $('#get_categories').DataTable({
                ordering: false,
                bDestroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 50,
                ajax: {
                    url: '{{url("admin/orders/flters")}}/?name=' + name +
                        '&phone=' + phone + '&payment_method=' + payment_method +
                        '&payment_status=' + payment_status + '&status=' + status +
                        '&id_order=' + id_order +'&created_at=' + created_at+'&created_at_to=' + created_at_to +'&country_id=' + Countryd,
                },
                lengthMenu: [
                    [10, 50 , 200 , 500 , 1000 ,  -1],
                    [10, 50 , 200 , 500 , 1000],
                ],
                createdRow: function( row, data, dataIndex ) {
                    if (data.comment != null){
                        $( row ).find('td').css('background-color' , '#fdd');
                    }
                },
                columns: [
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {

                            return `<input type="checkbox" name="check" class="che" value="${data.id}"/>`;
                        },
                    },
                    {
                        'data': 'id',
                    },
                    {
                        'data': null,
                        'classxName': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if (data.user) {
                                return data.user.first_name ?? "" + " " + data.user.last_name;
                            } else {
                                return "";
                            }

                        },
                    },
                    {
                        'data': null,
                        'classxName': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if (data.address) {
                                if(data.address.region_data){
                                    bb = data.address.region_data.title_ar;
                                }else {
                                    bb = "غير موجود" + data.address.region_id;
                                }
                            } else {
                                bb = "لم يتم تحديد المنطقة";
                            }
                            return bb;

                        },
                    },
                    {
                        'data': null,
                        'classxName': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return  data.user.country.title_ar ?? "";
                        },

                    },
                    {
                        'data': 'user.orders_count',

                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (row, data, type) {
                            if (row.status == 'new') {
                                return `<button class="btn btn-secondary btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.new') }}</button>`;
                            } else if (row.status == 'pay_pending') {
                                return `<button class="btn btn-info btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.pay_pending') }}</button>`;
                            } else if (row.status == 'shipping') {
                                return `<button class="btn btn-purple btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.shipping') }}</button>`;
                            } else if (row.status == 'shipping_complete') {
                                return `<button class="btn btn-success-gradient btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.shipping_complete') }}</button>`;
                            } else if (row.status == 'complete') {
                                return `<button class="btn btn-success btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.complete') }}</button>`;
                            } else {
                                return '';
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {

                            if(data.payment_id == '6'){
                                return `<p>${data.payment.title_en}</p>`;
                            }else {

                                if (data.payment) {
                                    if (local == 'en') {
                                        if (data.payment_status == '0') {
                                            return `<p>${data.payment.title_en}</p><hr><div style="color: red">(فشل الدفع)</div>`;
                                            // return data.payment.title_en +  "n" + "("+ "فشل الدفع" +")";
                                        }else if(data.payment_status == '1'){
                                            return `<p>${data.payment.title_en}</p><hr><div style="color: blue">(تم الدفع)</div>`;
                                        } else {
                                            return `<p>${data.payment.title_en}</p><hr><div style="color: darkslategrey">(معلق لدفع)</div>`;


                                            // return data.payment.title_en +  "\n" + "("+ "تم الدفع" +")";
                                        }
                                    } else {
                                        if (data.payment_status == '0') {
                                            return `<p>${data.payment.title_en}</p><hr><div style="color: red">(فشل الدفع)</div>`;
                                            // return data.payment.title_en +  "n" + "("+ "فشل الدفع" +")";
                                        }else if(data.payment_status == '1'){
                                            return `<p>${data.payment.title_en}</p><hr><div style="color: blue">(تم الدفع)</div>`;
                                        } else {
                                            return `<p>${data.payment.title_en}</p><hr><div style="color: darkslategrey">(معلق لدفع)</div>`;


                                            // return data.payment.title_en +  "\n" + "("+ "تم الدفع" +")";
                                        }
                                    }
                                } else {
                                    return "";
                                }

                            }
                        },
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data) {

                            if(data.total_cost){
                                return data.total_cost ;
                            }else {
                                return '';
                            }

                        },

                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {

                            return `<a class="modal-effect btn btn-outline-success"  href="{{url('ar/admin/appUser/notify2')}}/?user_id=${data.user_id}" ><i class="fe fe-bell"></i></a>`;
                        },
                    },
                    {
                        'data': null,

                        render: function (data, row, type) {
                            var name = [];
                            var price = [];
                            var count = [];


                            if (data.pieces) {
                                data.pieces.forEach(function (item) {
                                    name.push(item.clothe.title_ar ?? "");
                                    price.push(item.clothe.price ?? "");
                                    count.push(item.clothe_count ?? "");
                                });
                            } else {
                                name.push("");
                                price.push("");
                                count.push("");
                            }
                            var b = "";
                            if (data.payment) {
                                b = data.payment.title_en;
                            } else {
                                b = "";
                            }
                            var bb = "";
                            if (data.address) {
                                if(data.address.region_data){
                                    bb = data.address.region_data.title_ar;
                                }else {
                                    bb = "غير موجود" + data.address.region_id;
                                }
                                // console.log(bb);
                            } else {
                                bb = "";
                            }
                            var bbb = "";
                            if (data.delivery_type_title) {
                                if(data.delivery_type_title.id == '1'){
                                    bbb = data.delivery_type_title.title_ar + " \/" + data.delivery_date + " " + data.time_id;
                                }else{
                                    bbb = data.delivery_type_title.title_ar;
                                }
                            } else {
                                bbb = 'الشحن دولي';
                            }
                            var bbbb = "";
                            var bbbbb = "";
                            var bbbbbb = "";
                            if (data.user) {
                                bbbb = data.user.id;
                                bbbbb = data.user.first_name;
                                bbbbbb = data.user.mobile_number;
                            } else {
                                bbbb = "";
                                bbbbb = "";
                                bbbbbb = "";
                            }
                            if (data.pieces){
                                var pieces_count= data.user.orders_count;

                            }
                            if (data.created_at){
                                const d = new Date(data.created_at);

                                var created= d.toLocaleString();



                            }

                            return `
                        @can('order-view')
                            <button class="btn btn-success btn-sm" id="Management" data-credit_userds="${ data.return_credit ?? 0 }" data-id="${data.id}" data-created="${created}" data-payment="${data.payment.title_ar ?? ''}" data-country_name="${data.user.country.title_ar ?? ''}"
                                data-address="${bb ?? ''}" data-delivery_type="${bbb}" data-user_id="${bbbb}"
                                data-user_name="${bbbbb}" data-note="${data.notes ?? ''}"
                                data-mobile_number="${bbbbbb}" data-user_agent="${data.user_agent ?? ''}" data-total_cost="${data.total_cost ?? ''}" data-total_last="${data.user.orders_sum_total_cost ?? ''}" data-pieces_count="${pieces_count ?? ''}" ><i class="fa fa-clipboard"></i> {{ trans('orders.Details') }}</button>
                        @endcan
                            @can('order-update')
                            <button class="btn btn-info btn-sm" id="prodects" data-id="${data.id}" data-prodects="${name}" data-price="${price}" data-count="${count}" ><i class="las la-clipboard"> {{ trans('clothes.Prodect') }} </i></button>
                        <button class="btn btn-purple btn-sm" id="addnote" data-id="${data.id}" data-comment="${data.comment ?? ''}"  ><i class="las la-clipboard"> {{ trans('orders.note') }} </i></button>
                        @endcan
                            @can('order-delete')
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}"><i class="las la-trash"></i></button>
                        @endcan
                            `;
                        },

                    },
                ],
            });


        });

        var table = $('#get_categories').DataTable({
            ordering: false,
            bDestroy: true,
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: 50,
            ajax: {
                url: '{{ url("admin/orders/flters") }}',
                // cache: true

            },
            lengthMenu: [
                [10, 50 , 200 , 500 , 1000 ,  -1],
                [10, 50 , 200 , 500 , 1000],
            ],
            createdRow: function( row, data, dataIndex ) {
                if (data.comment != null){
                    $( row ).find('td').css('background-color' , '#fdd');
                }
            },
            columns: [
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {

                        return `<input type="checkbox" name="check" class="che" value="${data.id}"/>`;
                    },
                },
                {
                    'data': 'id',
                },
                {
                    'data': null,
                    'classxName': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (data.user) {
                            return data.user.first_name ?? "" + " " + data.user.last_name;
                        } else {
                            return "";
                        }

                    },
                },
                {
                    'data': null,
                    'classxName': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (data.address) {
                            if(data.address.region_data){
                                bb = data.address.region_data.title_ar;
                            }else {
                                bb = "غير موجود" + data.address.region_id;
                            }
                        } else {
                            bb = "لم يتم تحديد المنطقة";
                        }
                        return bb;

                    },
                },
                {
                    'data': null,
                    'classxName': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                         return  data.user.country.title_ar ?? "";
                    },

                },
                {
                    'data': 'user.orders_count',

                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (row, data, type) {
                        if (row.status == 'new') {
                            return `<button class="btn btn-secondary btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.new') }}</button>`;
                        } else if (row.status == 'pay_pending') {
                            return `<button class="btn btn-info btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.pay_pending') }}</button>`;
                        } else if (row.status == 'shipping') {
                            return `<button class="btn btn-purple btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.shipping') }}</button>`;
                        } else if (row.status == 'shipping_complete') {
                            return `<button class="btn btn-success-gradient btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.shipping_complete') }}</button>`;
                        } else if (row.status == 'complete') {
                            return `<button class="btn btn-success btn-block" id="status" data-id="${row.id}" data-viewing_status="${row.status}">{{ trans('orders.complete') }}</button>`;
                        } else {
                            return '';
                        }
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {

                        if(data.payment_id == '6'){
                            return `<p>${data.payment.title_en}</p>`;
                        }else {

                        if (data.payment) {
                            if (local == 'en') {
                                if (data.payment_status == '0') {
                                    return `<p>${data.payment.title_en}</p><hr><div style="color: red">(فشل الدفع)</div>`;
                                    // return data.payment.title_en +  "n" + "("+ "فشل الدفع" +")";
                                }else if(data.payment_status == '1'){
                                    return `<p>${data.payment.title_en}</p><hr><div style="color: blue">(تم الدفع)</div>`;
                                } else {
                                    return `<p>${data.payment.title_en}</p><hr><div style="color: darkslategrey">(معلق لدفع)</div>`;


                                    // return data.payment.title_en +  "\n" + "("+ "تم الدفع" +")";
                                }
                            } else {
                                if (data.payment_status == '0') {
                                    return `<p>${data.payment.title_en}</p><hr><div style="color: red">(فشل الدفع)</div>`;
                                    // return data.payment.title_en +  "n" + "("+ "فشل الدفع" +")";
                                }else if(data.payment_status == '1'){
                                    return `<p>${data.payment.title_en}</p><hr><div style="color: blue">(تم الدفع)</div>`;
                                } else {
                                    return `<p>${data.payment.title_en}</p><hr><div style="color: darkslategrey">(معلق لدفع)</div>`;


                                    // return data.payment.title_en +  "\n" + "("+ "تم الدفع" +")";
                                }
                            }
                        } else {
                            return "";
                        }

                        }
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data) {

                        if(data.total_cost){
                            return data.total_cost ;
                        }else {
                            return '';
                        }

                    },

                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {

                        return `<a class="modal-effect btn btn-outline-success"  href="{{url('ar/admin/appUser/notify2')}}/?user_id=${data.user_id}" ><i class="fe fe-bell"></i></a>`;
                    },
                },
                {
                    'data': null,

                    render: function (data, row, type) {
                        var name = [];
                        var price = [];
                        var count = [];


                        if (data.pieces) {
                            data.pieces.forEach(function (item) {
                                name.push(item.clothe.title_ar ?? "");
                                price.push(item.clothe.price ?? "");
                                count.push(item.clothe_count ?? "");
                            });
                        } else {
                            name.push("");
                            price.push("");
                            count.push("");
                        }
                        var b = "";
                        if (data.payment) {
                            b = data.payment.title_en;
                        } else {
                            b = "";
                        }
                        var bb = "";
                        if (data.address) {
                            if(data.address.region_data){
                                bb = data.address.region_data.title_ar;
                            }else {
                                bb = "غير موجود" + data.address.region_id;
                            }
                            // console.log(bb);
                        } else {
                            bb = "";
                        }
                        var bbb = "";
                        if (data.delivery_type_title) {
                            if(data.delivery_type_title.id == '1'){
                                bbb = data.delivery_type_title.title_ar + " \/" + data.delivery_date + " " + data.time_id;
                            }else{
                                bbb = data.delivery_type_title.title_ar;
                            }
                        } else {
                            bbb = 'الشحن دولي';
                        }
                        var bbbb = "";
                        var bbbbb = "";
                        var bbbbbb = "";
                        if (data.user) {
                            bbbb = data.user.id;
                            bbbbb = data.user.first_name;
                            bbbbbb = data.user.mobile_number;
                        } else {
                            bbbb = "";
                            bbbbb = "";
                            bbbbbb = "";
                        }
                        if (data.pieces){
                            var pieces_count= data.user.orders_count;

                        }
                        if (data.created_at){
                            const d = new Date(data.created_at);

                            var created= d.toLocaleString();



                        }
                        return `
                        @can('order-view')
                        <button class="btn btn-success btn-sm" id="Management" data-id="${data.id}" data-created="${created}" data-credit_userds="${ data.return_credit ?? 0 }" data-payment="${data.payment.title_ar ?? ''}" data-country_name="${data.user.country.title_ar ?? ''}"
                                data-address="${bb ?? ''}" data-delivery_type="${bbb}" data-user_id="${bbbb}"
                                data-user_name="${bbbbb}" data-note="${data.notes ?? ''}"
                                data-mobile_number="${bbbbbb}" data-user_agent="${data.user_agent ?? ''}" data-total_cost="${data.total_cost ?? ''}" data-total_last="${data.user.orders_sum_total_cost ?? ''}" data-pieces_count="${pieces_count ?? ''}" ><i class="fa fa-clipboard"></i> {{ trans('orders.Details') }}</button>
                        @endcan
                        @can('order-update')
                        <button class="btn btn-info btn-sm" id="prodects" data-id="${data.id}" data-prodects="${name}" data-price="${price}" data-count="${count}" ><i class="las la-clipboard"> {{ trans('clothes.Prodect') }} </i></button>
                        <button class="btn btn-purple btn-sm" id="addnote" data-id="${data.id}" data-comment="${data.comment ?? ''}"  ><i class="las la-clipboard"> {{ trans('orders.note') }} </i></button>
                        @endcan
                        @can('order-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}"><i class="las la-trash"></i></button>
                        @endcan
                        `;
                    },

                },
            ],


        });
        // console.log( table.columns(data.id));




        $(document).on('click', '#DeleteCategory', function (e) {
            e.preventDefault();
            $('#usernamed').val($(this).data('id'));
            var id_admin = $(this).data('id');
            $('#modaldemo8').modal('show');
            aaaa(id_admin);
        });

        function aaaa(id) {
            $(document).off("click", "#dletet").on("click", "#dletet", function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ url("admin/orders/delete") }}/' + id,
                    data: '',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                        $('#modaldemo8').modal('hide');
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            });
        }

        $(document).on('click', '#status', function (e) {
            e.preventDefault();
            // console.log("Alliiiii");
            var edit_id = $(this).data('id');
            var status = $(this).data('viewing_status');
            if (status == 1) {
                status = 0;
            } else {
                status = 1;
            }
            var data = {
                id: edit_id,
                status: status
            };
            // console.log(status);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("update.status") }}',
                data: data,
                success: function (response) {
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-danger");
                    $('#error_message').text(response.message);
                    // table.ajax.reload();
                    table.draw(false);
                }
            });
        });

        $(document).on('click', '#Management', function (e) {
            e.preventDefault();
            $('#exampleModal2').modal('show');
            var id = $(this).data('id');
            var created = $(this).data('created');
            var payment = $(this).data('payment');
            var address = $(this).data('address');
            var delivery_type = $(this).data('delivery_type');
            var user_id = $(this).data('user_id');
            var user_name = $(this).data('user_name');
            var mobile_number = $(this).data('mobile_number');
            var user_agent = $(this).data('user_agent');
            var note = $(this).data('note');
            var totalinvoices = $(this).data('total_cost');
            var total_last = $(this).data('total_last');
            var pieces_count = $(this).data('pieces_count');
            var country_name = $(this).data('country_name');
            var credit_userds = $(this).data('credit_userds');

            $('#Numberorders').text(id);
            $('#date').text(created);
            $('#Payment').text(payment);
            $('#Governorate').text(address);
            $('#Totalinvoices').text(totalinvoices);
            $('#Total').text(total_last);
            $('#Delivery_time').text(delivery_type);
            $('#Customer_Number').text(user_id);
            $('#Name').text(user_name);
            $('#Mobile_number').text(mobile_number);
            $('#Device_type').text(user_agent);
            $('#pieces_count').text(pieces_count);
            $('#country_name').text(country_name);
            $('#credit_userds').text(credit_userds);
            $('#Notes').text(note);
            $('#PDFFF').click(function (e) {
                // e.preventDefault();
                // console.log('dsadsad');
                // var id = $('#Management').data('id');
                $(this).attr('href', "{{ url('admin/DownloadOrderPDF') }}/" + id);
            });
        });

        $(document).on('click', '#Delete_all', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $('#modaldemo100').modal('show');
                $(document).on('click', '#dletet100', function (e) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route("ordersAll.delete") }}' + '/?type=1',
                        data: {'ids': ids},
                        success: function (response) {
                            $('#error_message').html("");
                            $('#error_message').addClass("alert alert-danger");
                            $('#error_message').text(response.message);
                            $('#modaldemo100').modal('hide');
                            // table.ajax.reload();
                            table.draw(false);
                        }
                    });
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }

        });


        $(document).on('click', '#print_orders', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=8',
                    data: {'ids': ids},
                    success: function (response) {
                        window.open('{{ url('/test')  }}?ids='+ids+'');
                        {{--window.location.replace('{{ url('/test')  }}?ids='+ids);--}}
                        // console.log(response);
                        table.draw(false);
                    }
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }
        });

        $(document).on('click', '#Request_Accept', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=2',
                    data: {'ids': ids},
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }
        });

        $(document).on('click', '#Order_processing', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=3',
                    data: {'ids': ids},
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }
        });

        $(document).on('click', '#Cancelling_order', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=4',
                    data: {'ids': ids},
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }
        });

        $(document).on('click', '#Charged', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=5',
                    data: {'ids': ids},
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }
        });

        $(document).on('click', '#Receipt_confirmed', function (e) {
            var ids = [];
            $('input[type=checkbox][name=check]').each(function () {
                if (($(this)).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            if (ids.length > '0') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=6',
                    data: {'ids': ids},
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            } else {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text("pleace select");
            }
        });

        $(document).on('click', '#prodects', function (e) {
            const boxes = document.querySelectorAll('.box');
            boxes.forEach(box => {
                box.remove();
            });
            const h2 = document.getElementById("showprod");
            var beforprodect = $(this).data('prodects');
            var beforprice = $(this).data('price');
            var beforcount = $(this).data('count').toString();
            // var aftercount = "";
            // if (Array.isArray(beforcount)) {
            //     // var beforcount_count = $(this).data('count');
            //     const re = /\s*(?:,|$)\s*/;
            //     // beforcount = beforcount.toString();
            //     aftercount = beforcount.split(re);
            //     console.log(aftercount);
            // } else {
            //      aftercount = [beforcount];
            //     console.log(aftercount);
            // }
            const afterprodects = beforprodect.split(',');
            const afterprice = beforprice.split(',');
            // const aftercount = [0]
            // if(beforcount){
                const aftercount = beforcount.split(',');
            // }

            // if (beforcount.split(',')) {
            //     const aftercount = beforcount.split(',');
            // } else {
            //     aftercount = beforcount;
            // }
            for (var i = 0; i < afterprodects.length; i++) {
                let html = ` <tr class="box" > <th>${afterprodects[i]}</th> <td>${afterprice[i]}</td> <td>${aftercount[i]}</td> </tr>`;
                h2.insertAdjacentHTML("afterbegin", html);
            }
            $('#showProdects').modal('show');
        });

        $(document).on('click', '#addnote', function (e) {
            e.preventDefault();
            var idd = $(this).data('id');
            var comment = $(this).data('comment');
            $('#notes').val(comment);
            $('#note').modal('show');
            Addnoteww(idd);
            {{--$(document).on('click', '.Addnoteww', function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    let formdata = new FormData($('#formnote')[0]);--}}
            {{--    var data = {--}}
            {{--        notes: $('#notes').val()--}}
            {{--    };--}}
            {{--    $.ajaxSetup({--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        }--}}
            {{--    });--}}
            {{--    $.ajax({--}}
            {{--        type: 'DELETE',--}}
            {{--        url: '{{ route("ordersAll.delete") }}' + '/?type=7&idw=' + idd + '&notes=' + $('#notes').val(),--}}
            {{--        data: data,--}}
            {{--        contentType: false,--}}
            {{--        processData: false,--}}
            {{--        success: function (response) {--}}
            {{--            $('#error_message').html("");--}}
            {{--            $('#error_message').addClass("alert alert-info");--}}
            {{--            $('#error_message').text(response.message);--}}
            {{--            $('#note').modal('hide');--}}
            {{--            table.ajax.reload();--}}
            {{--        }--}}
            {{--    });--}}
            {{--    --}}{{--$.ajax({--}}
            {{--    --}}{{--    type: 'POST',--}}
            {{--    --}}{{--    enctype: "multipart/form-data",--}}
            {{--    --}}{{--    url: '{{ route("add_prodect") }}',--}}
            {{--    --}}{{--    data: formdata,--}}
            {{--    --}}{{--    contentType: false,--}}
            {{--    --}}{{--    processData: false,--}}
            {{--    --}}{{--    success: function(response) {--}}
            {{--    --}}{{--        --}}
            {{--    --}}{{--    }--}}
            {{--    --}}{{--});--}}

            {{--});--}}
        });

        function Addnoteww(id) {
            $(document).off("click", ".Addnoteww").on("click", ".Addnoteww", function (e) {
                e.preventDefault();
                var data = {
                    notes: $('#notes').val()
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route("ordersAll.delete") }}' + '/?type=7&idw=' + id + '&notes=' + $('#notes').val(),
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        $('#note').modal('hide');
                        // table.ajax.reload();
                        table.draw(false);
                    }
                });
            });
        }
    </script>

@endsection
