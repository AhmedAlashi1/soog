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

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Home</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                Clients</span>
        </div>

    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <div class="main-body">
<div id="error_message"></div>
<div class="modal" id="modalAddClient">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Categories</h6><button aria-label="Close" class="close" data-dismiss="modal"
                    type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formclient">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">User Name :</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">USER Email :</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">USER Phone :</label>
                            <input type="number" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label"> User Status :</label>
                            <select name="status" id="select-beast" class="form-control">
                                <option value="1" id="ClientActive">Active</option>
                                <option value="0" id="ClientNotActive">Not Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success AddClient" id="AddClient">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Basic modal -->
<div class="modal" id="modalEditClient">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Categories</h6><button aria-label="Close" class="close" data-dismiss="modal"
                    type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formeditadmin">
                    <input type="hidden" class="form-control" id="id_client">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">User Name :</label>
                            <input type="text" class="form-control" id="client_name" name="name" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">USER Email :</label>
                            <input type="email" class="form-control" id="client_email" name="email" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">USER Phone :</label>
                            <input type="number" class="form-control" id="client_phone" name="phone" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label"> User Status :</label>
                            <select name="status" id="client_status" class="form-control">
                                <!-- <option value="0" id="ClientNotActive"></option> -->
                                <option value="1" id="ClientActive">Active</option>
                                <option value="0" id="ClientNotActive">Not Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="EditClient">Save</button>
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
                <div class="row row-xs wd-xl-80p">
                    <div class="col-sm-6 col-md-3 mg-t-10">
                        <button class="btn btn-info-gradient btn-block" id="ShowModalAddClient">
                            <a href="#" style="font-weight: bold; color: beige;">Add User</a>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                    <table class="table table-hover" id="get_clients" style=" text-align: center;">
                        <thead>
                            <tr>
                                <th class="wd-10p border-bottom-0">#</th>
                                <th class="wd-15p border-bottom-0">User Name</th>
                                <th class="wd-20p border-bottom-0">user email</th>
                                <th class="wd-15p border-bottom-0">Phone</th>
                                <th class="wd-15p border-bottom-0">User Status</th>
                                <th class="wd-10p border-bottom-0">Processes</th>
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
<!--/div-->
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
var table = $('#get_clients').DataTable({
    // processing: true,
    ajax: '{!! route("get_clients") !!}',
    columns: [{
            'data': 'id',
            'className': 'text-center text-lg text-medium'
        },
        {
            'data': 'name',
            'className': 'text-center text-lg text-medium'
        },
        {
            'data': 'email',
            'className': 'text-center text-lg text-medium'
        },
        {
            'data': null,
            render: function(data, row, type) {
                var phone;
                if (data.phone) {
                    phone = data.phone
                } else {
                    phone = "No num"
                }
                return phone;
            },
        },
        {
            'data': null,
            render: function(data, row, type) {
                var phone;
                if (data.status == '1') {
                    return `<button class="btn btn-success-gradient btn-block">Active</button>`;
                } else {
                    return `<button class="btn btn-danger-gradient btn-block">Not Active</button>`;
                }
            },
        },
        {
            'data': null,
            render: function(data, row, type) {
                return `<button class="modal-effect btn btn-sm btn-info" id="ShowModalEditClient" data-id="${data.id}"><i class="las la-pen"></i></button>
                                <button class="modal-effect btn btn-sm btn-danger" id="DeleteClient" data-id="${data.id}"><i class="las la-trash"></i></button>`;
            },
            orderable: false,
            searchable: false
        },
    ],
});
//  view modal admin
$(document).on('click', '#ShowModalAddClient', function(e) {
    e.preventDefault();
    $('#modalAddClient').modal('show');
});
// create admin
$(document).on('click', '.AddClient', function(e) {
    e.preventDefault();
    let formdata = new FormData($('#formclient')[0]);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '{{ route("add_client") }}',
        data: formdata,
        contentType: false,
        processData: false,
        success: function(response) {
            // console.log("Done");
            $('#AddClient').text('Saving');
            $('#error_message').html("");
            $('#error_message').addClass("alert alert-info");
            $('#error_message').text(response.message + " " + response.data.name);
            $('#modalAddClient').modal('hide');
            $('#formclient')[0].reset();
            table.ajax.reload();
        }
    });
    $.ajaxError()
});
// view modification data
$(document).on('click', '#ShowModalEditClient', function(e) {
    e.preventDefault();
    var id_client = $(this).data('id');
    $('#modalEditClient').modal('show');
    $.ajax({
        type: 'GET',
        url: '{{ url("dashbord/client/edit") }}/' + id_client,
        data: "",
        success: function(response) {
            console.log(response);
            if (response.status == 404) {
                console.log('error');
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text(response.message);
            } else {
                $('#id_client').val(id_client);
                $('#client_name').val(response.data.name);
                $('#client_email').val(response.data.email);
                $('#client_phone').val(response.data.phone);
                if (response.data.status == '1') {
                    $("select option[value='1']").attr("selected", "selected");
                } else {
                    $("select option[value='0']").attr("selected", "selected");
                }
            }
        }
    });
});
$(document).on('click', '#EditClient', function(e) {
    e.preventDefault();
    var data = {
        name: $('#client_name').val(),
        email: $('#client_email').val(),
        phone: $('#client_phone').val(),
        status: $('#client_status').val(),
    };
    // let formdata = new FormData($('#formeditadmin')[0]);
    var id_client = $('#id_client').val();
    console.log(data);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '{{ url("dashbord/client/update") }}/' + id_client,
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.status == 400) {
                // errors
                $('#list_error_messagee').html("");
                $('#list_error_messagee').addClass("alert alert-danger");
                $.each(response.errors, function(key, error_value) {
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
                $('#modalEditClient').modal('hide');
                // $('#FormEditWork')[0].reset();
                table.ajax.reload();
            }
        }
    });
});
$(document).on('click', '#DeleteClient', function(e) {
    e.preventDefault();
    var id_client = $(this).data('id');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'DELETE',
        url: '{{ url("dashbord/client/delete") }}/' + id_client,
        data: '',
        contentType: false,
        processData: false,
        success: function(response) {
            $('#error_message').html("");
            $('#error_message').addClass("alert alert-danger");
            $('#error_message').text(response.message);
            table.ajax.reload();
        }
    });
});
</script>


@endsection
