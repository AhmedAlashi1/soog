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
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
          integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous"/>

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('areas.Governorate_title') }}</span>
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
        <div class="modal" id="modalAddCategory">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('category.content_title') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message"></ul>
                        <form id="formcategory" enctype="multipart/form-data">
                            <input type="hidden" name="user_iddd">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.type') }} :</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="2"></option>
                                        <option value="1">منزل</option>
                                        <option value="2">شقة</option>
                                        <option value="3">عمل</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.Governorate') }} :</label>
                                    <select class="form-control" name="governate" id="governate">
                                        <option value=""></option>
                                        @foreach($Governorates as $key)
                                            @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                                <option value="{{ $key->id }}">{{ $key->title_en }}</option>
                                            @else
                                                <option value="{{ $key->id }}">{{ $key->title_ar }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {{--                                    <input type="text" class="form-control" name="governate" required>--}}
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.Region') }} :</label>
                                    {{--                                    <input type="text" class="form-control" name="city" required>--}}
                                    <select class="form-control" name="city" id="city">
                                        <option value=""></option>
                                        @foreach($Cities as $key)
                                            @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                                <option value="{{ $key->id }}">{{ $key->title_en }}</option>
                                            @else
                                                <option value="{{ $key->id }}">{{ $key->title_ar }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.block') }} :</label>
                                    <input type="text" class="form-control" name="block" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.avenue') }} :</label>
                                    <input type="text" class="form-control" name="avenue" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.street') }} :</label>
                                    <input type="text" class="form-control" name="street" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.building') }} :</label>
                                    <input type="text" class="form-control" name="building" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.aldawr') }} :</label>
                                    <input type="text" class="form-control" name="floor" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.home') }} :</label>
                                    <input type="text" class="form-control" name="flat" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.Notes') }} :</label>
                                    <textarea type="text" class="form-control" name="notes" required></textarea>
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
                        <h6 class="modal-title">{{ trans('category.content_title') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message2"></ul>
                        <form id="formeditadmin" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" id="id_categoryd" name="id_categoryd">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.type') }} :</label>
                                    <select class="form-control" name="type" id="typed">
                                        <option value="2"></option>
                                        <option value="1">منزل</option>
                                        <option value="2">شقة</option>
                                        <option value="3">عمل</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 selectgovernated">
                                    <label for="exampleInputEmail1">{{ trans('areas.Governorate') }} :</label>
                                    <select class="form-control" name="governate" id="governated">
                                        <option value=""></option>
                                        @foreach($Governorates as $key)
                                            @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                                <option value="{{ $key->id }}">{{ $key->title_en }}</option>
                                            @else
                                                <option value="{{ $key->id }}">{{ $key->title_ar }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {{--                                    <input type="text" class="form-control" name="governate" required>--}}
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.Region') }} :</label>
                                    {{--                                    <input type="text" class="form-control" name="city" required>--}}
                                    <select class="form-control" name="city" id="cityd">
                                        <option value=""></option>
                                        @foreach($Cities as $key)
                                            @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                                <option value="{{ $key->id }}">{{ $key->title_en }}</option>
                                            @else
                                                <option value="{{ $key->id }}">{{ $key->title_ar }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.block') }} :</label>
                                    <input type="text" class="form-control" name="block" id="blockd" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.avenue') }} :</label>
                                    <input type="text" class="form-control" name="avenue" id="avenued" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.street') }} :</label>
                                    <input type="text" class="form-control" name="street" id="streetd" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.building') }} :</label>
                                    <input type="text" class="form-control" name="building" id="buildingd" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.aldawr') }} :</label>
                                    <input type="text" class="form-control" name="floor" id="floord" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.home') }} :</label>
                                    <input type="text" class="form-control" name="flat" id="flatd" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">{{ trans('areas.Notes') }} :</label>
                                    <textarea type="text" class="form-control" name="notes" id="notesd"
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
        <!-- row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card mg-b-20">
                    <div class="card-header pb-0">
                        @can('customer-create')
                            <div class="row row-xs wd-xl-80p">
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block" id="ShowModalAddCategory">
                                        <a href="#"
                                           style="font-weight: bold; color: beige;">{{ trans('areas.Add_Governorate') }}</a>
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('categories-view')
                                <table class="table table-hover" id="get_categories" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ trans('areas.Governorate') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.Region') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.widget') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.Avenue') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.street') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.building') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.aldawr') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.Flat') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.Notes') }}</th>
                                        <th class="border-bottom-0">{{ trans('areas.mobile') }}</th>
                                        <th class="border-bottom-0">
                                            @canany([ 'customer-update' , 'customer-delete' ])
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

    <script>
        var local = "{{ App::getLocale() }}";
        var id = "{{ request()->route('id') }}";
        var table = $('#get_categories').DataTable({
            // processing: true,
            ajax: '{{ url("admin/appUser/get_address") }}/' + id,
            columns: [
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (data.type == '1') {
                            return 'منزل';
                        } else if (data.type == '2') {
                            return 'شقة';
                        } else {
                            return 'عمل';
                        }
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (local == 'en') {
                            if (data.city_data) {
                                return data.city_data.title_en ?? "";
                            } else {
                                return "";
                            }
                        } else {
                            if (data.city_data) {
                                return data.city_data.title_ar ?? "";
                            } else {
                                return "";
                            }
                        }
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (local == 'en') {
                            if (data.region_data) {
                                return data.region_data.title_en ?? "";
                            } else {
                                return "";
                            }
                        } else {
                            if (data.region_data) {
                                return data.region_data.title_ar ?? "";
                            } else {
                                return "";
                            }
                        }
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.block ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.avenue ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.street ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.building ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.floor ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.flat ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.notes ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.mobile ?? "";
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        return `
                        @can('customer-update')
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                        @endcan
                        @can('customer-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.address}"><i class="las la-trash"></i></button>
                        @endcan
                        `;
                    },

                },
            ],
        });


        {{--//  view modal Category--}}
        $(document).on('click', '#ShowModalAddCategory', function (e) {
            e.preventDefault();
            $('#modalAddCategory').modal('show');
        });
        {{--// Category admin--}}
        $(document).on('click', '.AddCategory', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formcategory')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/appUser/add_address") }}/' + id + '/0',
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
                        $('#error_message').addClass("alert alert-success");
                        $('#error_message').text(response.message);
                        $('#modalAddCategory').modal('hide');
                        $('#formcategory')[0].reset();
                        table.ajax.reload();
                    }

                }
            });
        });
        {{--// view modification data--}}
        $(document).on('click', '#ShowModalEditCategory', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#modalEditCategory').modal('show');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/appUser/add_address/") }}/' + id + '/1',
                data: "",
                success: function (response) {
                    console.log(response);
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {
                        // $('.selectgovernated option[value="100"]').attr('selected', 'selected');
                        $('#id_categoryd').val(id);
                        $('#typed').val(response.data.type);
                        $('#governated').val(response.data.city_id);
                        $('#cityd').val(response.data.region_id);
                        $('#blockd').val(response.data.block);
                        $('#avenued').val(response.data.avenue);
                        $('#streetd').val(response.data.street);
                        $('#buildingd').val(response.data.building);
                        $('#floord').val(response.data.floor);
                        $('#flatd').val(response.data.flat);
                        $('#notesd').val(response.data.notes);
                    }
                }
            });
        });
        $(document).on('click', '#EditClient', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formeditadmin')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/appUser/add_address/") }}/' + id + '/2',
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
                        $('#error_message').addClass("alert alert-success");
                        $('#error_message').text(response.message);
                        $('#modalEditCategory').modal('hide');
                        table.ajax.reload();
                    }
                }
            });
        });

        {{--$(document).on('click', '#status', function(e) {--}}
        {{--    e.preventDefault();--}}
        {{--    // console.log("Alliiiii");--}}
        {{--    var edit_id = $(this).data('id');--}}
        {{--    var status = $(this).data('viewing_status');--}}
        {{--    if(status == 1){--}}
        {{--        status = 0;--}}
        {{--    }else{--}}
        {{--        status = 1;--}}
        {{--    }--}}
        {{--    var data = {--}}
        {{--        id: edit_id,--}}
        {{--        status: status--}}
        {{--    };--}}
        {{--    // console.log(status);--}}
        {{--    $.ajaxSetup({--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        }--}}
        {{--    });--}}
        {{--    $.ajax({--}}
        {{--        type: 'POST',--}}
        {{--        url: '{{ route("update.status") }}',--}}
        {{--        data: data,--}}
        {{--        success: function(response) {--}}
        {{--            // $('#error_message').html("");--}}
        {{--            // $('#error_message').addClass("alert alert-danger");--}}
        {{--            // $('#error_message').text(response.message);--}}
        {{--            table.ajax.reload();--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
        {{--$(document).on('click', '#statusoff', function(e) {--}}
        {{--    e.preventDefault();--}}
        {{--    // console.log("Alliiiii");--}}
        {{--    var edit_id = $(this).data('id');--}}
        {{--    var status = $(this).data('viewing_status');--}}
        {{--    if(status == 1){--}}
        {{--        status = 0;--}}
        {{--    }else{--}}
        {{--        status = 1;--}}
        {{--    }--}}
        {{--    var data = {--}}
        {{--        id: edit_id,--}}
        {{--        status: status--}}
        {{--    };--}}
        {{--    // console.log(status);--}}
        {{--    $.ajaxSetup({--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        }--}}
        {{--    });--}}
        {{--    $.ajax({--}}
        {{--        type: 'POST',--}}
        {{--        url: '{{ route("update.status") }}',--}}
        {{--        data: data,--}}
        {{--        success: function(response) {--}}
        {{--            // $('#error_message').html("");--}}
        {{--            // $('#error_message').addClass("alert alert-danger");--}}
        {{--            // $('#error_message').text(response.message);--}}
        {{--            table.ajax.reload();--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
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
                    url: '{{ url("admin/appUser/add_address/delete") }}/' + id,
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

        $(document).ready(function () {
            $('#governate').selectize({
                sortField: 'text'
            });
            $('#city').selectize({
                sortField: 'text'
            });
            $('#governated').selectize({
                sortField: 'text'
            });
            $('#cityd').selectize({
                sortField: 'text'
            });
        });
    </script>

@endsection
