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
                <h4 class="content-title mb-0 my-auto">{{ trans('coupons.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                {{trans('menu.deliveryTypes')}}</span>
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

        <div class="modal" id="modalAddDeliveryType">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('deliveryTypes.page_title') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formDeliveryType" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.Worktime_E') }} :</label>
                                    <input type="text" class="form-control" name="title_en" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.Worktime_A') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.time_from') }} :</label>
                                    <input type="time" class="form-control" name="time_from">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.time_to') }} :</label>
                                    <input type="time" class="form-control" name="time_to">
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="checkbox" name="sat" value="1">
                                    <label for="sat">{{ trans('deliveryTypes.sat') }}</label>
                                    <input type="checkbox" name="sun" value="1">
                                    <label for="sun">{{ trans('deliveryTypes.sun') }}</label>
                                    <input type="checkbox" name="mon" value="1">
                                    <label for="mon">{{ trans('deliveryTypes.mon') }}</label>
                                    <input type="checkbox" name="tue" value="1">
                                    <label for="tue">{{ trans('deliveryTypes.tue') }}</label>
                                    <input type="checkbox" name="wed" value="1">
                                    <label for="wed">{{ trans('deliveryTypes.wed') }}</label>
                                    <input type="checkbox" name="thu" value="1">
                                    <label for="thu">{{ trans('deliveryTypes.thu') }}</label>
                                    <input type="checkbox" name="fri" value="1">
                                    <label for="fri">{{ trans('deliveryTypes.fri') }}</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddDeliveryType"
                                        id="AddDeliveryType">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
        <div class="modal" id="modalEditDeliveryType">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('deliveryTypes.page_title') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="ali" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="id_DeliveryType">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.Worktime_E') }} :</label>
                                    <input type="text" class="form-control" name="title_en" id="title_en" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.Worktime_A') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" id="title_ar" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.time_from') }} :</label>
                                    <input type="time" class="form-control" id="time_from" name="time_from">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('deliveryTypes.time_to') }} :</label>
                                    <input type="time" class="form-control" id="time_to" name="time_to">
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="checkbox" id="sat" name="sat" value="1">
                                    <label for="sat">{{ trans('deliveryTypes.sat') }}</label>

                                    <input type="checkbox" id="sun" name="sun" value="1">
                                    <label for="sun">{{ trans('deliveryTypes.sun') }}</label>


                                    <input type="checkbox" id="mon" name="mon" value="1">
                                    <label for="mon">{{ trans('deliveryTypes.mon') }}</label>


                                    <input type="checkbox" id="tue" name="tue" value="1">
                                    <label for="tue">{{ trans('deliveryTypes.tue') }}</label>


                                    <input type="checkbox" id="wed" name="wed" value="1">
                                    <label for="wed">{{ trans('deliveryTypes.wed') }}</label>


                                    <input type="checkbox" id="thu" name="thu" value="1">
                                    <label for="thu">{{ trans('deliveryTypes.thu') }}</label>


                                    <input type="checkbox" id="fri" name="fri" value="1">
                                    <label for="fri">{{ trans('deliveryTypes.fri') }}</label>
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
                        <div class="row row-xs wd-xl-80p">
                            @can('deliveryHour-create')
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block" id="ShowModalAddDeliveryType">
                                        <a href="#"
                                           style="font-weight: bold; color: beige;">{{ trans('deliveryTypes.add') }}</a>
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('deliveryHour-view')
                                <table class="table table-hover" id="get_DeliveryTypes" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ trans('app_users.name') }}</th>
                                        <th class="border-bottom-0">{{ trans('app_users.status') }}</th>
                                        <th class="border-bottom-0">
                                            @canany([ 'categories-update' , 'categories-delete' ])
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
    <script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
    <script>
        var local = "{{ App::getLocale() }}";
        var i = 1;
        var table = $('#get_DeliveryTypes').DataTable({
            // processing: true,
            ajax: '{!! route("get_deliveryTypes") !!}',
            lengthMenu: [
                [10, 50 , 200 , 500 , 1000 ,  -1],
                [10, 50 , 200 , 500 , 1000],
            ],
            columns: [{
                'data': 'id',
                'className': 'text-center text-lg text-medium'
            },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (local == "en") {
                            return data.title_en;
                        } else {
                            return data.title_ar;
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
                @can('deliveryHour-update')
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditDeliveryType" data-id="${data.id}"><i class="las la-pen"></i></button>
                @endcan
                @can('deliveryHour-delete')
                <button class="modal-effect btn btn-sm btn-danger" id="DeleteDeliveryType" data-id="${data.id}" @if(\Illuminate\Support\Facades\App::getLocale() == 'en')data-namee="${data.title_en}"@else data-namee="${data.title_ar}"@endif><i class="las la-trash"></i></button>
                @endcan
                        `;
                    },
                    orderable: false,
                    searchable: false
                },
            ],
        });
        //  view modal DeliveryType
        $(document).on('click', '#ShowModalAddDeliveryType', function (e) {
            e.preventDefault();
            $('#modalAddDeliveryType').modal('show');
        });
        // DeliveryType admin
        $(document).on('click', '.AddDeliveryType', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formDeliveryType')[0]);
            // console.log(formdata);
            // console.log("formdata");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("add_deliveryTypes") }}',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log("Done");
                    $('#AddDeliveryType').text('Saving');
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-info");
                    $('#error_message').text(response.message);
                    $('#modalAddDeliveryType').modal('hide');
                    $('#formDeliveryType')[0].reset();
                    table.ajax.reload();
                }
            });
        });
        // view modification data
        $(document).on('click', '#ShowModalEditDeliveryType', function (e) {
            e.preventDefault();
            var id_DeliveryType = $(this).data('id');
            $('#modalEditDeliveryType').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("admin/deliveryTypes/edit") }}/' + id_DeliveryType,
                data: "",
                success: function (response) {
                    console.log(response);
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {
                        $('#ali')[0].reset();
                        $('#id_DeliveryType').val(id_DeliveryType);
                        $('#title_en').val(response.data.title_en);
                        $('#title_ar').val(response.data.title_ar);
                        $('#time_from').val(response.data.time_from);
                        $('#time_to').val(response.data.time_to);
                        if (response.data.sat == 1) {
                            $('#sat').attr('checked', 'checked');
                        } else {
                            $('#sat').removeAttr('checked');
                        }
                        if (response.data.sun == 1) {
                            $('#sun').attr('checked', 'checked');
                        } else {
                            $('#sun').removeAttr('checked');
                        }
                        if (response.data.mon == 1) {
                            $('#mon').attr('checked', 'checked');
                        } else {
                            $('#mon').removeAttr('checked');
                        }
                        if (response.data.tue == 1) {
                            $('#tue').attr('checked', 'checked');
                        } else {
                            $('#tue').removeAttr('checked');
                        }
                        if (response.data.wed == 1) {
                            $('#wed').attr('checked', 'checked');
                        } else {
                            $('#wed').removeAttr('checked');
                        }
                        if (response.data.thu == 1) {
                            $('#thu').attr('checked', 'checked');
                        } else {
                            $('#thu').removeAttr('checked');
                        }
                        if (response.data.fri == 1) {
                            $('#fri').attr('checked', 'checked');
                        } else {
                            $('#fri').removeAttr('checked');
                        }
                    }
                }
            });
        });
        $(document).on('click', '#EditClient', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#ali')[0]);
            var id_DeliveryType = $('#id_DeliveryType').val();
            console.log(formdata);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/deliveryTypes/update") }}/' + id_DeliveryType,
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    if (response.status == 400) {
                        // errors
                        $('#list_error_messagee').html("");
                        $('#list_error_messagee').addClass("alert alert-danger");
                        $.each(response.errors, function (key, error_value) {
                            $('#list_error_messagee').append('<li>' + error_value + '</li>');
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
                        $('#modalEditDeliveryType').modal('hide');
                        table.ajax.reload();
                    }
                }
            });
        });

        {{--$(document).on('click', '#DeleteDeliveryType', function(e) {--}}
        {{--    e.preventDefault();--}}
        {{--    var id_DeliveryType = $(this).data('id');--}}
        {{--    $.ajaxSetup({--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        }--}}
        {{--    });--}}
        {{--    $.ajax({--}}
        {{--        type: 'DELETE',--}}
        {{--        url: '{{ url("admin/deliveryTypes/delete") }}/' + id_DeliveryType,--}}
        {{--        data: '',--}}
        {{--        contentType: false,--}}
        {{--        processData: false,--}}
        {{--        success: function(response) {--}}
        {{--            $('#error_message').html("");--}}
        {{--            $('#error_message').addClass("alert alert-danger");--}}
        {{--            $('#error_message').text(response.message);--}}
        {{--            table.ajax.reload();--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

        $(document).on('click', '#DeleteDeliveryType', function (e) {
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
                    url: '{{ url("admin/deliveryTypes/delete") }}/' + id,
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
                url: '{{ route("deliveryTypes.status") }}',
                data: data,
                success: function (response) {

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
                url: '{{ route("deliveryTypes.status") }}',
                data: data,
                success: function (response) {

                    table.ajax.reload();
                }
            });
        });
    </script>
@endsection
