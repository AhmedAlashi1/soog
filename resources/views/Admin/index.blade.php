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

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{trans('menu.users')}}</span>
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
        <div class="modal" id="modalAddAdmin">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{trans('menu.users')}}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formadmin">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('admins.user_name') }} :</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('admins.email') }} :</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                {{--                        <div class="form-group col-md-12">--}}
                                {{--                            <label for="exampleInputEmail1">{{ trans('admins.phone') }} :</label>--}}
                                {{--                            <input type="number" class="form-control" id="phone" name="phone" required>--}}
                                {{--                        </div>--}}
                                <div class="form-group col-md-12">
                                    <label class="form-label"> {{ trans('admins.permission') }} :</label>
                                    <select name="role" class="form-control">
                                        @foreach($role as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('admins.password') }} :</label>
                                    <input type="text" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddAdmin" id="AddAdmin">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
        <div class="modal" id="modalEditAdmin">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{trans('menu.users')}}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formeditadmin">
                            <input type="hidden" class="form-control" id="id_admin">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('admins.user_name') }} :</label>
                                    <input type="text" class="form-control" id="admin_name" name="name" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('admins.email') }} :</label>
                                    <input type="email" class="form-control" id="admin_email" name="email" required>
                                </div>
                                {{--                        <div class="form-group col-md-12">--}}
                                {{--                            <label for="exampleInputEmail1">{{ trans('admins.phone') }} :</label>--}}
                                {{--                            <input type="number" class="form-control" id="admin_phone" name="phone" required>--}}
                                {{--                        </div>--}}
                                <div class="form-group col-md-12">
                                    <label class="form-label"> {{ trans('admins.permission') }} :</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value=""></option>
                                        @foreach($role as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('admins.password') }} :</label>
                                    <input type="text" class="form-control" id="admin_password" name="admin_password" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="EditAdmin">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
        <!-- row opened -->
        <div class="row row-sm">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        @can('member-create')
                            <div class="row row-xs wd-xl-80p">
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block" id="ShowModalAddAdmin">
                                        <a href="#"
                                           style="font-weight: bold; color: beige;">{{ trans('admins.Add_User') }}</a>
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('member-view')
                                <table class="table table-hover" id="get_admins" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="wd-10p border-bottom-0">#</th>
                                        <th class="wd-10p border-bottom-0">dsadasd</th>
                                        <th class="wd-15p border-bottom-0">{{ trans('admins.user_name') }}</th>
                                        <th class="wd-20p border-bottom-0">{{ trans('admins.email') }}</th>
                                        {{--                                <th class="wd-15p border-bottom-0">{{ trans('admins.phone') }}</th>--}}
                                        <th class="wd-10p border-bottom-0">
                                            @canany([ 'member-update' , 'member-delete' ])
                                                {{ trans('admins.Processes') }}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script>

        var table = $('#get_admins').DataTable({
            // processing: true,
            ajax: '{!! route("get_admins") !!}',
            lengthMenu: [
                [10, 50 , 200 , 500 , 1000 ,  -1],
                [10, 50 , 200 , 500 , 1000],
            ],
            columns: [
                {
                    'data': 'id',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        var itemm = "";
                        if(data.roles){
                            data.roles.forEach(function (item){
                                itemm = item.name ?? "";
                            });
                        }else{
                            return itemm;
                        }
                        return itemm;
                    },
                },
                {
                    'data': 'name',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': 'email',
                    'className': 'text-center text-lg text-medium'
                },
                // {
                //     'data': null,
                //     render: function(data, row, type) {
                //         var phone;
                //         if (data.mobile) {
                //             phone = data.mobile
                //         } else {
                //             phone = "No num"
                //         }
                //         return phone;
                //     },
                // },
                {
                    'data': null,
                    render: function (data, row, type) {
                        if(data.id == 1){
                            return `
                        @can('member-update')
                            <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditAdmin" data-id="${data.id}"><i class="las la-pen"></i></button>
                        @endcan

                            `;
                        }
                        else{   return `
                        @can('member-update')
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditAdmin" data-id="${data.id}"><i class="las la-pen"></i></button>
                        @endcan
                        @can('member-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteAdmin" data-id="${data.id}" data-namee="${data.name}"><i class="las la-trash"></i></button>
                        @endcan
                        `;}


                    },
                    orderable: false,
                    searchable: false
                },
            ],
        });
        //  view modal admin
        $(document).on('click', '#ShowModalAddAdmin', function (e) {
            e.preventDefault();
            $('#modalAddAdmin').modal('show');
        });
        // create admin
        $(document).on('click', '.AddAdmin', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formadmin')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/admin/add") }}',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log("Done");
                    $('#AddAdmin').text('Saving');
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-info");
                    $('#error_message').text(response.message + " " + response.data.name);
                    $('#modalAddAdmin').modal('hide');
                    $('#formadmin')[0].reset();
                    table.ajax.reload();
                }
            });
            $.ajaxError()
        });
        // view modification data
        $(document).on('click', '#ShowModalEditAdmin', function (e) {
            e.preventDefault();
            var id_admin = $(this).data('id');
            $('#modalEditAdmin').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url('admin/admin/edit') }}/' + id_admin,
                data: "",
                success: function (response) {
                    console.log(response);
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {
                        var $select = $("#role").selectize();
                        var selectiz = $select[0].selectize;
                        if(response.data.roles[0]){
                            selectiz.setValue(selectiz.search(response.data.roles[0].name).items[0].id);
                        }
                        $('#id_admin').val(id_admin);
                        $('#admin_name').val(response.data.name);
                        $('#admin_email').val(response.data.email);
                        $('#admin_phone').val(response.data.mobile);
                        // $('#admin_password').val(response.data.password);
                    }
                }
            });
        });
        $(document).on('click', '#EditAdmin', function (e) {
            e.preventDefault();
            var data = {
                name: $('#admin_name').val(),
                email: $('#admin_email').val(),
                // phone: $('#admin_phone').val(),
                role: $('#role').val(),
                password: $('#admin_password').val(),
            };
            // let formdata = new FormData($('#formeditadmin')[0]);
            var id_admin = $('#id_admin').val();
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url('admin/admin/update') }}/' + id_admin,
                data: data,
                dataType: 'json',
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
                        $('#EditAdmin').text('Saving');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-info");
                        $('#error_message').text(response.message);
                        $('#modalEditAdmin').modal('hide');
                        // $('#FormEditWork')[0].reset();
                        table.ajax.reload();
                    }
                }
            });
        });

        {{--$(document).on('click', '#DeleteAdmin', function (e) {--}}
        {{--            e.preventDefault();--}}
        {{--            var id_admin = $(this).data('id');--}}
        {{--            $.ajaxSetup({--}}
        {{--                headers: {--}}
        {{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--                }--}}
        {{--            });--}}
        {{--            $.ajax({--}}
        {{--                type: 'DELETE',--}}
        {{--                url: '{{ url('admin/admin/delete/') }}/' + id_admin,--}}
        {{--                data: '',--}}
        {{--                contentType: false,--}}
        {{--                processData: false,--}}
        {{--                success: function (response) {--}}
        {{--                    $('#error_message').html("");--}}
        {{--                    $('#error_message').addClass("alert alert-danger");--}}
        {{--                    $('#error_message').text(response.message);--}}
        {{--                    table.ajax.reload();--}}
        {{--                }--}}
        {{--            });--}}
        {{--        });--}}

        $(document).on('click', '#DeleteAdmin', function (e) {
            e.preventDefault();
            $('#usernamed').val($(this).data('namee'));
            var id_admin = $(this).data('id');
            $('#modaldemo8').modal('show');
            $(document).on('click', '#dletet', function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: '{{ url('admin/admin/delete/') }}/' + id_admin,
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
        });
    </script>

@endsection
