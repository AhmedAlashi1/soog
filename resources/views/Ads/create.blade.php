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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <link href="{{URL::asset('assets/plugins/prism/prism.css')}}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{URL::asset('assets/plugins/inputtags/inputtags.css')}}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('notification.content_titl')}}</span>
            </div>

        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div id="error_message"></div>
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    @if(Session::has('warning'))
                        <div class="alert alert-warning">
                            {{Session::get('warning')}}
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form id="formeditadmin" action="{{ route("add_ads") }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.title') }} :</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.url') }} :</label>
                                <input type="text" class="form-control" name="url" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.lauout_title') }} :</label>

                                {{--                            <select name="lauout_title" class="form-control" >--}}
                                <select name="layout" class="form-control"   >
                                    <option value="1">اعلى الرئيسيه الاول</option>
                                    <option value="3">اعلى الرئيسيه الثانى</option>
                                    <option value="6">صفحة اتمام الطلب</option>
                                    <option value="5">صفحة الاقسام</option>
                                    <option value="2">اسفل الرئيسيه</option>
                                    <option value="4">اعلى الرئيسيه قديم</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.days') }} :</label>
                                <input type="number" class="form-control" name="days" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.cost') }} :</label>
                                <input type="number" class="form-control" name="cost" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"> {{ trans('ads.cat') }} :</label>
                                <select name="cat_id" class="form-control">
                                    <option></option>
                                    @foreach($cat as $cat)
                                        @if(App::getLocale() == 'en')
                                            <option value="{{ $cat->id }}">{{ $cat->title_en }}</option>
                                        @else
                                            <option value="{{ $cat->id }}">{{ $cat->title_ar }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.prodect') }} :</label>
                                <input type="number" class="form-control" name="product_id" >
                            </div>
{{--                            <div class="form-group col-md-12">--}}
{{--                                <label class="form-label"> {{ trans('ads.prodect') }} :</label>--}}
{{--                                <select name="pro_id" class="form-control" >--}}
{{--                                    <option></option>--}}
{{--                                    @foreach($pro as $pro)--}}
{{--                                        @if(App::getLocale() == 'en')--}}
{{--                                            <option value="{{ $pro->id }}">{{ $pro->title_en }}</option>--}}
{{--                                        @else--}}
{{--                                            <option value="{{ $pro->id }}">{{ $pro->title_ar }}</option>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                            <div class="form-group col-md-12">--}}
{{--                                <label class="form-label"> {{ trans('ads.prodect') }} :</label>--}}
{{--                                <select name="multi_product_id[]" class="form-control" multiple>--}}
{{--                                    <option></option>--}}
{{--                                    @foreach($prod as $pro)--}}
{{--                                        @if(App::getLocale() == 'en')--}}
{{--                                            <option value="{{ $pro->id }}">{{ $pro->title_en }}</option>--}}
{{--                                        @else--}}
{{--                                            <option value="{{ $pro->id }}">{{ $pro->title_ar }}</option>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                            <div class="form-group col-md-12"  id="hidden">
                                <div class="text-wrap">
                                    <div class="example">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ trans('ads.prodect') }}</label>
{{--                                            <div class="bootstrap-tagsinput">--}}
{{--                                            </div>--}}
                                            <input type="text" id="testetset" name="multi_product_id" data-role="tagsinput"
                                                   class="form-control sr-only"  ></div>
                                    </div>
                                </div>
                            </div>
{{--                            <button id="test"> test </button>--}}
{{--                            <div class="form-group col-md-12" hidden="hidden" id="hidden">--}}
{{--                                <label for="exampleInputEmail1">{{ trans('notification.user') }}</label>--}}
{{--                                <input type="number" class="form-control" id="id_user" name="id_user">--}}
{{--                            </div>--}}
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('ads.models') }} :</label>
                                <input type="file" required  class="form-control" name="image">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">{{ trans('ads.Status') }} :</label>
                                <select name="status" class="form-control">
                                    <option value="1">{{ trans('category.Active') }}</option>
                                    <option value="0">{{ trans('category.iActive') }}</option>
                                </select>
                            </div>

{{--                            <div class="form-group col-md-12">--}}
{{--                                <label class="rdiobox"--}}
{{--                                       style="display: inline-block; padding-left: 10px; padding-right: 10px;"><input--}}
{{--                                            type="radio"   name="international" value="1">--}}
{{--                                    <span>{{ trans('notification.international') }}</span></label>--}}
{{--                                <label class="rdiobox"--}}
{{--                                       style="display: inline-block; padding-left: 10px; padding-right: 10px;"><input--}}
{{--                                            type="radio" checked name="international" value="2">--}}
{{--                                    <span>{{ trans('notification.notinternational') }}</span></label>--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-md-12">--}}
{{--                                <p>{{ trans('notification.send') }}</p>--}}
{{--                                <label class="rdiobox"--}}
{{--                                       style="display: inline-block; padding-left: 10px; padding-right: 10px;"><input--}}
{{--                                            type="radio" checked id="all" name="type" value="all">--}}
{{--                                    <span>{{ trans('notification.all') }}</span></label>--}}
{{--                                <label class="rdiobox"--}}
{{--                                       style="display: inline-block; padding-left: 10px; padding-right: 10px;"><input--}}
{{--                                            type="radio" id="customer" name="type" value="customer"  @if(request()->input('user_id')) checked="checked" @else @endif>--}}
{{--                                    <span>{{ trans('notification.users') }}</span></label>--}}
{{--                            </div>--}}
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"  >{{ trans('notification.sendt') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            @can('customer-view')
                <div class="row row-sm">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('clothes.export') }}" method="get">
                                    @csrf
                                    <div class="row mg-b-20">
                                        <div class="parsley-input col-md-3" id="fnWrapper">
                                            <label for="exampleInputEmail1">{{ trans('clothes.Title') }} :</label>
                                            <input type="text" class="form-control" id="named" name="name">
                                        </div>
                                        <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                            <label class="form-label"> {{ trans('clothes.cat') }} :</label>
                                            <input type="text" class="form-control" id="cat_idd" name="cat_idd">
                                            {{--                                        <select name="cat_idd" id="cat_idd" class="form-control">--}}
                                            {{--                                            <option value="">{{ trans('orders.all') }}</option>--}}
                                            {{--                                            @foreach($cat as $c)--}}
                                            {{--                                                @if(App::getLocale() == 'en')--}}
                                            {{--                                                    <option value="{{ $c->id }}">{{ $c->title_en }}</option>--}}
                                            {{--                                                @else--}}
                                            {{--                                                    <option value="{{ $c->id }}">{{ $c->title_ar }}</option>--}}
                                            {{--                                                @endif--}}
                                            {{--                                            @endforeach--}}
                                            {{--                                        </select>--}}
                                        </div>
                                        <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                            <label>{{trans('category.Status')}} :</label>
                                            <select class="form-control form-control-md mg-b-20" id="statusd" name="status">
                                                <option value="">{{ trans('orders.all') }}</option>
                                                <option value="1">{{ trans('category.Active') }}</option>
                                                <option value="2">{{ trans('category.iActive') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                            id="s">{{ trans('orders.Sarech') }}</button>
                                    @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                        <button type="submit" class="btn btn-success float-right">Excel</button>
                                    @else
                                        @can('product-create')
                                            <button type="submit" class="btn btn-success float-left">Excel</button>
                                        @endcan
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="row">


                <div class="col-xl-12">
                    <div class="card mg-b-20">
                        <div class="card-header pb-0">
                            @can('product-create')
{{--                                <div class="row row-xs wd-xl-80p">--}}
{{--                                    <div class="col-sm-6 col-md-3 mg-t-10">--}}
{{--                                        <button class="btn btn-info-gradient btn-block" id="ShowModalAddCategory">--}}
{{--                                            <a href="#"--}}
{{--                                               style="font-weight: bold; color: beige;">{{ trans('clothes.Add') }}</a>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive hoverable-table">
                                @can('product-view')
                                    <table class="table table-hover" id="get_Prodects" style=" text-align: center;">
                                        <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ trans('clothes.Image') }}</th>
                                            <th class="border-bottom-0">{{ trans('clothes.cat') }}</th>
                                            <th class="border-bottom-0 wd-15p">{{ trans('clothes.Prodect') }}</th>
                                            <th class="border-bottom-0">{{ trans('clothes.Price') }}</th>
                                            <th class="border-bottom-0">{{ trans('clothes.Quntaty') }}</th>
                                            <th class="border-bottom-0">{{ trans('clothes.weight') }}</th>
                                            <th class="border-bottom-0">{{ trans('clothes.international') }}</th>
                                            <th class="border-bottom-0">{{ trans('clothes.Status') }}</th>
                                            <th class="border-bottom-0">
                                                @canany([ 'product-update' , 'product-delete' ])
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
        </div>
    </div>
@endsection

@section('js')
    <script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!-- Internal Select2 js-->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <!-- Internal Input tags js-->
    <script src="{{URL::asset('assets/plugins/inputtags/inputtags.js')}}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{URL::asset('assets/plugins/clipboard/clipboard.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/clipboard/clipboard.js')}}"></script>
    <!-- Internal Prism js-->
    <script src="{{URL::asset('assets/plugins/prism/prism.js')}}"></script>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script>
        var local = "{{ App::getLocale() }}";
        $('#s').click(function (e) {
            e.preventDefault();
            console.log('asadlasd');
            var status = $('#statusd').val();
            var name = $('#named').val();
            var cat_id = $('#cat_idd').val();
            var lang = local;
            // console.log(status + '//' + name + '//' + cat_id);
            $('#get_Prodects').DataTable({
                bDestroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 20,
                ajax: {
                    url: '{{ url("admin/clothes/get") }}' + '/?status=' + status + '&name=' + name
                        + '&cat_id=' + cat_id + '&lang=' + lang,
                    cache: true
                },
                columns: [
                    {
                        'data': 'id',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            if (data.image) {
                                return `<img src="{{url('/assets/tmp/')}}/${data.image}" style="width: 40px;height: 40px">`;
                            } else {
                                return "No Image";
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            if (data.categories) {
                                if (local == 'en') {
                                    return data.categories.title_en ?? "";
                                } else {
                                    return data.categories.title_ar ?? "";
                                }
                            } else {
                                return "";
                            }
                        },
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if (local == "en") {
                                return data.title_en ?? "";
                            } else {
                                return data.title_ar ?? "";
                            }
                        },
                    },
                    {
                        'data': 'price',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': 'quntaty',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': 'weight',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            if (data.international == '1') {
                                return `<button class="modal-effect btn btn-sm btn-success international" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                            } else {
                                return `<button class="modal-effect btn btn-sm  btn-danger international" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            var phone;
                            if (data.status == '1') {
                                return `<button class="btn btn-success-gradient btn-block" id="status" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;
                            } else {
                                return `<button class="btn btn-danger-gradient btn-block" id="statusoff" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return `
                @can('product-update')
                            <form action="{{ url('admin/add100/clothes') }}/${data.id}" method="post" style="display:inline;">
                @csrf
                            <button type="submit" class="btn btn-warning btn-sm"><i class="las la-plus"></i> 100</button>
                            </form>
                            <form action="{{ url('admin/minas100/clothes') }}/${data.id}" method="post" style="display:inline;">
                @csrf
                            <button type="submit" class="btn btn-purple btn-sm"><i class="las la-s">-</i> 100</button>
                            </form>
                            <button class="modal-effect btn btn-sm btn-secondary updateType" data-id="${data.id}"><i class="fa fa-bars"></i></button>
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                @endcan
                            @can('product-delete')
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.title_ar}"><i class="las la-trash"></i></button>
                @endcan
                            `;
                        },
                        // columns: [
                        //     { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false },
                        //     {data: 'name', name: 'name'},
                        //     {data: 'status', name: 'status'},
                        //     {data: 'created_at', name: 'created_at'},
                        //     {data: 'actions', name: 'actions'}
                        // ],
                    },
                ],

            });
        });

        var table = $('#get_Prodects').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: 20,
            ajax: '{{ route("get_prodect") }}',
            columns: [
                {
                    'data': 'id',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        if (data.image) {
                            return `<img src="{{url('/assets/tmp/')}}/${data.image}" style="width: 40px;height: 40px">`;
                        } else {
                            return "No Image";
                        }
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        if (data.categories) {
                            if (local == 'en') {
                                return data.categories.title_en ?? "";
                            } else {
                                return data.categories.title_ar ?? "";
                            }
                        } else {
                            return "";
                        }
                    },
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (local == "en") {
                            return data.title_en ?? "";
                        } else {
                            return data.title_ar ?? "";
                        }
                    },
                },
                {
                    'data': 'price',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': 'quntaty',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': 'weight',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        if (data.international == '1') {
                            return `<button class="modal-effect btn btn-sm btn-success  international" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                        } else {
                            return `<button class="modal-effect btn btn-sm btn-danger international" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                        }
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        var phone;
                        if (data.status == '1') {
                            return `<button class="btn btn-success-gradient btn-block" id="status" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;
                        } else {
                            return `<button class="btn btn-danger-gradient btn-block" id="statusoff" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                        }
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        return `
                @can('product-update')
                        <form action="{{ url('admin/add100/clothes') }}/${data.id}" method="post" style="display:inline;">
                @csrf
                        <button type="submit" class="btn btn-warning btn-sm"><i class="las la-plus"></i> 100</button>
                        </form>
                        <form action="{{ url('admin/minas100/clothes') }}/${data.id}" method="post" style="display:inline;">
                @csrf
                        <button type="submit" class="btn btn-purple btn-sm"><i class="las la-s">-</i> 100</button>
                        </form>
                        <button class="modal-effect btn btn-sm btn-secondary updateType" data-id="${data.id}"><i class="fa fa-bars"></i></button>
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                @endcan
                        @can('product-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.title_ar}"><i class="las la-trash"></i></button>
                @endcan
                        `;
                    },
                    // columns: [
                    //     { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false },
                    //     {data: 'name', name: 'name'},
                    //     {data: 'status', name: 'status'},
                    //     {data: 'created_at', name: 'created_at'},
                    //     {data: 'actions', name: 'actions'}
                    // ],
                },
            ],
        });
        //  view modal Category
        $(document).on('click', '#ShowModalAddCategory', function (e) {
            e.preventDefault();
            $('#modalAddCategory').modal('show');
        });
        // Category admin
        $(document).on('click', '.AddCategory', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formcategory')[0]);
            // console.log(formdata);
            // console.log("formdata");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                enctype: "multipart/form-data",
                url: '{{ url("admin/clothes/add/?typeer=1") }}',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log("Done");
                    if (response.status == 400) {
                        // errors
                        $('#list_error_message').html("");
                        $('#list_error_message').addClass("alert alert-danger");
                        $.each(response.errors, function (key, error_value) {
                            $('#list_error_message').append('<li>' + error_value + '</li>');
                        });
                    } else {
                        $('#AddCategory').text('Saving');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        $('#modalAddCategory').modal('hide');
                        $('#formcategory')[0].reset();
                        table.ajax.reload();
                    }
                }
            });
        });
        // view modification data
        $(document).on('click', '#ShowModalEditCategory', function (e) {
            e.preventDefault();
            var id_prodect = $(this).data('id');
            $('#modalEditCategory').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("admin/clothes/edit") }}/' + id_prodect,
                data: "",
                success: function (response) {
                    console.log(response);
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {
                        var $select = $("#cccat_id").selectize();
                        var selectiz = $select[0].selectize;
                        if(response.data.categories){
                            selectiz.setValue(selectiz.search(response.data.categories.title_ar).items[0].id);
                        }
                        var $select2 = $("#ttttype").selectize();
                        var selectiz2 = $select2[0].selectize;
                        if(response.data.type == 1){
                            selectiz2.setValue(selectiz2.search('الاكثر مبيعا').items[0].id);
                        }else if(response.data.type == 2){
                            selectiz2.setValue(selectiz2.search('عروض الاسبوع').items[0].id);
                        }
                        else if(response.data.type == 3){
                            selectiz2.setValue(selectiz2.search('الاحدث').items[0].id);
                        }
                        else if(response.data.type == 4){
                            selectiz2.setValue(selectiz2.search('قد ترغب بها').items[0].id);
                        }else{
                            selectiz2.setValue(selectiz2.search('').items[0].id);
                        }

                        $('#id_prodect').val(id_prodect);
                        $('#title_en').val(response.data.title_en);
                        $('#title_ar').val(response.data.title_ar);
                        $('#nota_en').val(response.data.note_en);
                        $('#nota_ar').val(response.data.note_ar);
                        $('#price').val(response.data.price);
                        $('#quntaty').val(response.data.quntaty);
                        $('#weight').val(response.data.weight);
                        $('#price_after').val(response.data.price_after);
                        $('#order_limit').val(response.data.order_limit);
                        $('#order_limit_user').val(response.data.order_limit_user);
                        $('.bootstrap-tagsinput input').val(response.data.keywords);
                    }
                }
            });
        });
        $(document).on('click', '#EditClient', function (e) {
            e.preventDefault();
            var data = {
                title_en: $('#title_en').val(),
                title_ar: $('#title_ar').val(),
                nota_en: $('#nota_en').val(),
                nota_ar: $('#nota_ar').val(),
                keywords: $('#keywords').val(),
                price: $('#price').val(),
                quntaty: $('#quntaty').val(),
                image: $('#image').val(),
                status: $('#status').val(),
            };
            let formdata = new FormData($('#formeditadmin')[0]);
            var id_prodect = $('#id_prodect').val();
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/clothes/update/") }}/' + id_prodect,
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    if (response.status == 400) {
                        // errors
                        $('#list_error_message2').html("");
                        $('#list_error_message2').addClass("alert alert-danger");
                        $.each(response.errors, function (key, error_value) {
                            $('#list_error_message2').append('<li>' + error_value + '</li>');
                        });
                    } else if (response.status == 404) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {
                        $('#EditClient').text('Saving');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        $('#modalEditCategory').modal('hide');
                        table.ajax.reload();
                    }
                }
            });
        });
        $(document).on('click', '#DeleteCategory', function (e) {
            e.preventDefault();
            $('#usernamed').val($(this).data('namee'));
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
                    url: '{{ url("admin/clothes/delete") }}/' + id,
                    data: '',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                        $('#modaldemo8').modal('hide');
                        table.ajax.reload();
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
                url: '{{ route("prodect.status") }}',
                data: data,
                success: function (response) {
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-danger");
                    $('#error_message').text(response.message);
                    table.ajax.reload();
                }
            });
        });
        $(document).on('click', '#statusoff', function (e) {
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
                url: '{{ route("prodect.status") }}',
                data: data,
                success: function (response) {
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-danger");
                    $('#error_message').text(response.message);
                    table.ajax.reload();
                }
            });
        });
        $(document).ready(function () {
            $('#nncat_id').selectize({
                sortField: 'text'
            });
            $('#cccat_id').selectize({
                sortField: 'text'
            });
        });

        $(document).on('click', '.updateType', function (e) {
            e.preventDefault();
            var id_prodect = $(this).data('id');
            $('#updateTypeModal').modal('show');
            UpdateTypeCategory(id_prodect);
        });
        function UpdateTypeCategory(id) {

            $(document).off("click", ".UpdateTypeCategory").on("click", ".UpdateTypeCategory", function (e) {
                e.preventDefault();
                var data = {
                    typeupdatemm: $('#typeupdatemm').val()
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/clothes/add/?typeer=2") }}&id=' + id,
                    data: data,
                    // contentType: false,
                    // processData: false,
                    success: function (response) {
                        $('#AddCategory').text('Saving');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        $('#updateTypeModal').modal('hide');
                        $('#formUpdateType')[0].reset();
                        table.ajax.reload();
                    }
                });
            });
        }
        $(document).on('click', '.international', function (e) {
            e.preventDefault();
            // console.log("Alliiiii");
            var edit_id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("clothes/update/status") }}'+'?typeint=1&id='+edit_id,
                data: "",
                success: function (response) {
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-danger");
                    $('#error_message').text(response.message);
                    table.ajax.reload();
                }
            });
        });

    </script>


@endsection
