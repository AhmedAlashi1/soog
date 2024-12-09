@extends('layouts.master')
@section('css')

@section('title')
المستخدمين
@stop

<!-- Internal Data table css -->

<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
      integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous"/>



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
            <h4 class="content-title mb-0 my-auto">{{ trans('clothes.Home') }}</h4><span
                class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                {{ trans('clothes.page_title') }}</span>
        </div>

    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <div class="main-body">
<div id="error_message"></div>
<div class="modal" id="modaldemo8" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('admins.dele') }}</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ trans('admins.aresure') }}</p><br>
                        <input class="form-control" name="usernamed" id="usernamed" type="text" readonly="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admins.close') }}</button>
                        <button type="submit" class="btn btn-danger" id="dletet">{{ trans('admins.save') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalAddCategory">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('clothes.page_title') }}</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message"></ul>
                        <form id="formcategory" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Title_E') }} :</label>
                                    <input type="text" class="form-control" name="title_en" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Title_A') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label"> {{ trans('clothes.cat') }} :</label>
                                    <select id="nncat_id" name="cat_id" class="form-control">
                                        <option value=""></option>
                                        {!! $getSubCat  !!}
                                        {{--                                        @foreach($cat as $c)--}}
                                        {{--                                            @if(App::getLocale() == 'en')--}}
                                        {{--                                                <option value="{{ $c->id }}">{{ $c->title_en }}</option>--}}
                                        {{--                                            @else--}}
                                        {{--                                                <option value="{{ $c->id }}">{{ $c->title_ar }}</option>--}}
                                        {{--                                            @endif--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Price') }} :</label>
                                    <input type="number" class="form-control" name="price" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1"> price_after :</label>
                                    <input type="number" class="form-control" name="price_after" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Quntaty') }} :</label>
                                    <input type="number" class="form-control" name="quntaty" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.weight') }} :</label>
                                    <input type="number" class="form-control" name="weight" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Image') }} :</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label"> {{ trans('clothes.Status') }} :</label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{ trans('category.Active') }}</option>
                                        <option value="0">{{ trans('category.iActive') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label"> {{ trans('clothes.appear') }} :</label>
                                    <select class="form-control" name="type">
                                        <option value="0"></option>
                                        <option value="1"> {{ trans('clothes.best_seller') }}</option>
                                        <option value="2"> {{ trans('clothes.Offers_week') }}</option>
                                        <option value="3"> {{ trans('clothes.recent') }}</option>
                                        <option value="4"> {{ trans('clothes.you_may') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Description_E') }} :</label>
                                    <textarea class="form-control" name="note_en" rows="3" required></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Description_A') }} :</label>
                                    <textarea class="form-control" name="note_ar" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddCategory"
                                        id="AddCategory">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!-- End Basic modal -->
        <div class="modal" id="modalEditCategory">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('clothes.page_title') }}</h6><button aria-label="Close" class="close"
                                                                                              data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message2"></ul>
                        <form id="formeditadmin" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="id_prodect">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Title_E') }} :</label>
                                    <input type="text" class="form-control" id="title_en" name="title_en" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Title_A') }} :</label>
                                    <input type="text" class="form-control" id="title_ar" name="title_ar" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label"> {{ trans('clothes.cat') }} :</label>
                                    <select id="cccat_id" name="cat_id" class="form-control">
                                        <option value=""></option>
                                        {!! $getSubCat  !!}
                                        {{--                                        @foreach($cat as $c)--}}
                                        {{--                                            @if(App::getLocale() == 'en')--}}
                                        {{--                                                <option value="{{ $c->id }}">{{ $c->title_en }}</option>--}}
                                        {{--                                            @else--}}
                                        {{--                                                <option value="{{ $c->id }}">{{ $c->title_ar }}</option>--}}
                                        {{--                                            @endif--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Price') }} :</label>
                                    <input type="number" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1"> price_after :</label>
                                    <input type="number" class="form-control" id="price_after" name="price_after" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Quntaty') }} :</label>
                                    <input type="number" class="form-control" id="quntaty" name="quntaty" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.weight') }} :</label>
                                    <input type="number" class="form-control" id="weight" name="weight" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Image') }} :</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label"> {{ trans('clothes.appear') }} :</label>
                                    <select class="form-control" name="type">
                                        <option value="0"></option>
                                        <option value="1"> {{ trans('clothes.best_seller') }}</option>
                                        <option value="2"> {{ trans('clothes.Offers_week') }}</option>
                                        <option value="3"> {{ trans('clothes.recent') }}</option>
                                        <option value="4"> {{ trans('clothes.you_may') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Description_E') }} :</label>
                                    <textarea class="form-control" id="nota_en" name="note_en" rows="3"
                                              required></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('clothes.Description_A') }} :</label>
                                    <textarea class="form-control" id="nota_ar" name="note_ar" rows="3"
                                              required></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"
                                        id="EditClient">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!-- End Basic modal -->
        @can('product-view')
            <div class="row row-sm">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
{{--                                @csrf--}}
                                <div class="row mg-b-20">
{{--                                    <div class="parsley-input col-md-3" id="fnWrapper">--}}
{{--                                        <label for="exampleInputEmail1">{{ trans('clothes.Title') }} :</label>--}}
{{--                                        <input type="text" class="form-control" id="named" name="name">--}}
{{--                                    </div>--}}
{{--                                    <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">--}}
{{--                                        <label class="form-label"> {{ trans('clothes.cat') }} :</label>--}}
{{--                                        <input type="text" class="form-control" id="cat_idd" name="cat_idd">--}}
{{--                                    </div>--}}
                                    <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                        <label>{{trans('category.Status')}} :</label>
                                        <select class="form-control form-control-md mg-b-20" id="statusd" name="status">
                                            <option value="">{{ trans('orders.all') }}</option>
                                            <option value="1">{{ trans('category.Active') }}</option>
                                            <option value="2">{{ trans('category.iActive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary"
                                        id="s" data-ser="test">{{ trans('orders.Sarech') }}</button>
{{--                                @if(\Illuminate\Support\Facades\App::getLocale() == 'en')--}}
{{--                                    <button type="submit" class="btn btn-success float-right">Excel</button>--}}
{{--                                @else--}}
{{--                                    @can('product-create')--}}
{{--                                        <button type="submit" class="btn btn-success float-left">Excel</button>--}}
{{--                                    @endcan--}}
{{--                                @endif--}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
<!-- row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="row row-xs wd-xl-80p">
                    <div class="col-sm-6 col-md-3 mg-t-10">
                        <button class="btn btn-info-gradient btn-block" id="ShowModalAddCategory">
                            <a href="#" style="font-weight: bold; color: beige;">Add Prodect</a>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                    <table class="table table-hover" id="get_Prodects" style=" text-align: center;">
                        <thead>
                            <tr>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{ trans('clothes.Image') }}</th>
                                <th class="border-bottom-0">{{ trans('clothes.cat') }}</th>
                                <th class="border-bottom-0">{{ trans('clothes.Prodect') }}</th>
                                <th class="border-bottom-0">{{ trans('clothes.Price') }}</th>
                                <th class="border-bottom-0">{{ trans('clothes.Quntaty') }}</th>
                                <th class="border-bottom-0">{{ trans('clothes.weight') }}</th>
                                <th class="border-bottom-0">{{ trans('clothes.international') }}</th>                                <th class="border-bottom-0">{{ trans('clothes.Status') }}</th>
                                <th class="border-bottom-0">
                                    @canany([ 'product-update' , 'product-delete' ])
                                        {{ trans('category.Processes') }}
                                    @endcanany
                                </th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
            integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.15/api/row().show().js"></script>

    <script>
    var local = "{{ App::getLocale() }}";
    var id = "{{ request()->route('id') }}";
    // console.log(id);
    $('#s').click(function (e) {
        var status = $('#statusd').val();
        $('#get_Prodects').DataTable({
            bDestroy: true,
            processing: true,
            pageLength: 10,
            ajax: {
                url: '{!! url("admin/clothes/show")!!}/'+id+'?status='+status,
            },
            lengthMenu: [
                [10, 50 , 200 , 500 , 1000 ,  -1],
                [10, 50 , 200 , 500 , 1000],
            ],
            createdRow: function( row, data, dataIndex ) {
                $(row).attr('class', 'row1');
                $(row).attr('data-id' , data.id);
            },
            columns: [
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render:function (){
                        return `<i class="fa fa-sort"></i>`;
                    }
                },
                {
                    'data': 'id',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    render: function(data, row, type) {
                        if (data.image) {
                            return `<img
                        src="{{url('/assets/tmp/')}}/${data.image}"
                                        style="width: 40px;height: 40px">`;
                        } else {
                            return "No Image";
                        }
                    },
                },
                {
                    'data': 'categories.title_ar',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function(data, row, type) {
                        if (local == "en") {
                            return data.title_en;
                        } else {
                            return data.title_ar;
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
                    render: function(data, row, type) {
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
                    render: function(data, row, type) {
                        return `
                            <button class="btn btn-warning btn-sm" id="add100" data-id="${data.id}"><i class="las la-plus"></i> 100</button>
                            <button class="btn btn-purple btn-sm" id="minas100" data-id="${data.id}"><i class="las la-s">-</i> 100</button>
                            <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}"><i class="las la-trash"></i></button>`;
                    },
                    orderable: false,
                    searchable: false
                },
            ],
        });
    });
    var table = $('#get_Prodects').DataTable({
        bDestroy: true,
        processing: true,
        pageLength: 10,
        ajax: {
        url: '{!! url("admin/clothes/show")!!}/'+id,
        },
        lengthMenu: [
            [10, 50 , 200 , 500 , 1000 ,  -1],
            [10, 50 , 200 , 500 , 1000],
        ],
        createdRow: function( row, data, dataIndex ) {
            $(row).attr('class', 'row1');
            $(row).attr('data-id' , data.id);
        },
        columns: [
            {
                'data': null,
                'className': 'text-center text-lg text-medium',
                render:function (){
                    return `<i class="fa fa-sort"></i>`;
                }
            },
            {
                'data': 'id',
                'className': 'text-center text-lg text-medium'
            },
            {
                'data': null,
                render: function(data, row, type) {
                    if (data.image) {
                        return `<img
                        src="{{url('/assets/tmp/')}}/${data.image}"
                                        style="width: 40px;height: 40px">`;
                    } else {
                        return "No Image";
                    }
                },
            },
            {
                'data': 'categories.title_ar',
                'className': 'text-center text-lg text-medium'
            },
            {
                'data': null,
                'className': 'text-center text-lg text-medium',
                render: function(data, row, type) {
                    if (local == "en") {
                        return data.title_en;
                    } else {
                        return data.title_ar;
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
                render: function(data, row, type) {
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
                render: function(data, row, type) {
                    return `
                            <button class="btn btn-warning btn-sm" id="add100" data-id="${data.id}"><i class="las la-plus"></i> 100</button>
                            <button class="btn btn-purple btn-sm" id="minas100" data-id="${data.id}"><i class="las la-s">-</i> 100</button>
                            <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}"><i class="las la-trash"></i></button>`;
                },
                orderable: false,
                searchable: false
            },
        ],
    });

    $(document).off("click", "#add100").on("click", "#add100", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '{{ url("admin/add100/clothes") }}/' + id,
            data: '',
            contentType: false,
            processData: false,
            success: function (response) {
                // $('#error_message').html("");
                // $('#error_message').addClass("alert alert-danger");
                // $('#error_message').text(response.message);
                table.ajax.reload();
                // table.draw(false);
                // $('#get_Prodects').DataTable().draw(false);
            }
        });
    });
    $(document).off("click", "#minas100").on("click", "#minas100", function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '{{ url("admin/minas100/clothes") }}/' + id,
            data: '',
            contentType: false,
            processData: false,
            success: function (response) {
                // $('#error_message').html("");
                // $('#error_message').addClass("alert alert-danger");
                // $('#error_message').text(response.message);
                table.ajax.reload();
                // table.draw(false);
                // $('#get_Prodects').DataTable().draw(false);
            }
        });
    });
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
                // $('#get_Prodects').DataTable().draw(false);
                // table.draw(false);
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
                // table.draw(false);
                // $('#get_Prodects').DataTable().draw(false);
            }
        });
    });
    $( "#get_Prodects" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        update: function() {
            sendOrderToServer();
        }
    });
    function sendOrderToServer() {
        var order = [];
        var token = $('meta[name="csrf-token"]').attr('content');
        $('tr.row1').each(function (index, element) {
            order.push({
                id: $(this).attr('data-id'),
                position: index + 1
            });
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('prodects_sortable') }}",
            data: {
                order: order,
                _token: token
            },
            success: function (response) {
                if (response.status == "success") {
                    console.log(response);
                } else {
                    console.log(response);
                }
            }
        });
    };
    //  view modal Category
    $(document).on('click', '#ShowModalAddCategory', function(e) {
        e.preventDefault();
        $('#modalAddCategory').modal('show');
    });
    // Category admin
    $(document).on('click', '.AddCategory', function(e) {
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
                    // table.ajax.reload();
                    // table.draw(false);
                    $('#get_Prodects').DataTable().draw(false);
                }
            }
        });
    });
    // view modification data
    $(document).on('click', '#ShowModalEditCategory', function(e) {
        e.preventDefault();
        var id_prodect = $(this).data('id');
        $('#modalEditCategory').modal('show');
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/clothes/edit") }}/' + id_prodect,
            data: "",
            success: function(response) {
                console.log(response);
                if (response.status == 404) {
                    console.log('error');
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-danger");
                    $('#error_message').text(response.message);
                } else {
                    $('#id_prodect').val(id_prodect);
                    $('#title_en').val(response.data.title_en);
                    $('#title_ar').val(response.data.title_ar);
                    $('#nota_en').val(response.data.note_en);
                    $('#nota_ar').val(response.data.note_ar);
                    $('#price').val(response.data.price);
                    $('#quntaty').val(response.data.quntaty);
                    $('#weight').val(response.data.weight);
                    $('#price_after').val(response.data.price_after);
                }
            }
        });
    });
    $(document).on('click', '#EditClient', function(e) {
        e.preventDefault();
        let formdata = new FormData($('#formeditadmin')[0]);
        var id_prodect = $('#id_prodect').val();
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
                    // $('#get_Prodects').DataTable().draw(false);
                }
            }
        });

    });
    $(document).on('click', '#DeleteCategory', function(e) {
        e.preventDefault();
        var id_prodect = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'DELETE',
            url: '{{ url("admin/clothes/delete") }}/' + id_prodect,
            data: '',
            contentType: false,
            processData: false,
            success: function(response) {
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text(response.message);
                table.ajax.reload();
                // $('#get_Prodects').DataTable().draw(false);
            }
        });
    });
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
                // $('#error_message').html("");
                // $('#error_message').addClass("alert alert-danger");
                // $('#error_message').text(response.message);
                table.ajax.reload();
                // table.draw(false);
                // $('#get_Prodects').DataTable().draw();
            }
        });
    });
    </script>


    @endsection
