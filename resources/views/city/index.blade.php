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
            <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('country.content_title') }}</span>
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

<div class="modal" id="modalAddcity">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ trans('country.content_title') }}</h6><button aria-label="Close" class="close" data-dismiss="modal"
                    type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formcity" enctype="multipart/form-data">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.city_name') }} :</label>
                            <input type="text" class="form-control" name="title_en" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.name_ar') }} :</label>
                            <input type="text" class="form-control" name="title_ar" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.delivery_cost') }} :</label>
                            <input type="number" class="form-control" name="delivery_cost" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.order_limit') }} :</label>
                            <input type="number" class="form-control" name="order_limit" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label"> {{ trans('country.Governorate') }} :</label>
                            <select name="governorat_id" class="form-control">
                                @foreach($Gov as $c)
                                    @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                        <option value="{{ $c->id }}">{{ $c->title_en }}</option>
                                    @else
                                        <option value="{{ $c->id }}">{{ $c->title_ar }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success Addcity" id="Addcity">{{ trans('country.save') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('country.close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Basic modal -->
<div class="modal" id="modalEditcity">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">cityes</h6><button aria-label="Close" class="close" data-dismiss="modal"
                    type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formeditadmin" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="id_city">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.city_name') }} :</label>
                            <input type="text" class="form-control" name="title_en" id="title_en" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.name_ar') }} :</label>
                            <input type="text" class="form-control" name="title_ar" id="title_ar" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.delivery_cost') }} :</label>
                            <input type="number" class="form-control" name="delivery_cost" id="delivery_cost" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">{{ trans('country.order_limit') }} :</label>
                            <input type="number" class="form-control" name="order_limit" id="order_limit" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label"> {{ trans('country.Governorate') }} : <span id="governora"></span></label>
                            <select name="governorat_id" class="form-control">
                                @foreach($Gov as $c)
                                    @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                                        <option value="{{ $c->id }}">{{ $c->title_en }}</option>
                                    @else
                                        <option value="{{ $c->id }}">{{ $c->title_ar }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="EditClient">{{ trans('country.save') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('country.close') }}</button>
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
                @can('region-create')
                <div class="row row-xs wd-xl-80p">
                    <div class="col-sm-6 col-md-3 mg-t-10">
                        <button class="btn btn-info-gradient btn-block" id="ShowModalAddcity">
                            <a href="#" style="font-weight: bold; color: beige;">{{ trans('country.addCities') }}</a>
                        </button>
                    </div>
                </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                    @can('region-view')
                    <table class="table table-hover" id="get_cities" style=" text-align: center;">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{ trans('country.zon_name') }}</th>
                                <th class="border-bottom-0">{{ trans('country.delivery_cost') }}</th>
                                <th class="border-bottom-0">{{ trans('country.order_limit') }}</th>
                                <th class="border-bottom-0">{{ trans('country.created_at') }}</th>
                                <th class="border-bottom-0">{{ trans('app_users.status') }}</th>
                                <th class="border-bottom-0">
                                    @canany([ 'region-update' , 'region-delete' ])
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
<script>
var local = "{{ App::getLocale() }}";
var table = $('#get_cities').DataTable({
    // processing: true,
    colReorder: true,
    order: [],
    pageLength: 0,
    ajax: '{!! route("get_cities") !!}',
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
            render: function(data, row, type) {
                if (local == "en") {
                    return data.title_en ?? "";
                } else {
                    return data.title_ar ?? "";
                }
            },
        },
        {
            'data': null,
            'className': 'text-center text-lg text-medium',
            render: function(data, row, type) {
                return data.delivery_cost ?? "";
            },
        },
        {
            'data': null,
            'className': 'text-center text-lg text-medium',
            render: function(data, row, type) {
                return data.order_limit ?? "";
            },
        },
        {
            'data': null,
            'className': 'text-center text-lg text-medium',
            render: function (data , row , type){
                var d = new Date(data.created_at);
                var datestring = d.getDate()  + "-" + (d.getMonth()+1) + "-" + d.getFullYear() + " " +
                    d.getHours() + ":" + d.getMinutes();
                return datestring;
            }
        },
        {
            'data': null,
            render: function(data, row, type) {
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
                @can('region-update')
                <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditcity" data-id="${data.id}"><i class="las la-pen"></i></button>
                @endcan
                @can('region-delete')
                <button class="modal-effect btn btn-sm btn-danger" id="Deletecity" data-id="${data.id}" @if(\Illuminate\Support\Facades\App::getLocale() == 'en')data-namee="${ data.title_en}"@else data-namee="${data.title_ar}"@endif><i class="las la-trash"></i></button>
                @endcan
                `;
            },
            orderable: false,
            searchable: false
        },
    ],
});
//  view modal city
$(document).on('click', '#ShowModalAddcity', function(e) {
    e.preventDefault();
    $('#modalAddcity').modal('show');
});
// city admin
$(document).on('click', '.Addcity', function(e) {
    e.preventDefault();
    let formdata = new FormData($('#formcity')[0]);
    // console.log(formdata);
    // console.log("formdata");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '{{ route("add_cities") }}',
        data: formdata,
        contentType: false,
        processData: false,
        success: function(response) {
            // console.log("Done");
            $('#Addcity').text('Saving');
            $('#error_message').html("");
            $('#error_message').addClass("alert alert-info");
            $('#error_message').text(response.message);
            $('#modalAddcity').modal('hide');
            $('#formcity')[0].reset();
            table.ajax.reload();
        }
    });
});
// view modification data
$(document).on('click', '#ShowModalEditcity', function(e) {
    e.preventDefault();
    var id_city = $(this).data('id');
    $('#modalEditcity').modal('show');
    $.ajax({
        type: 'GET',
        url: '{{ url("admin/city/edit") }}/' + id_city,
        data: "",
        success: function(response) {
            console.log(response);
            if (response.status == 404) {
                console.log('error');
                $('#error_message').html("");
                $('#error_message').addClass("alert alert-danger");
                $('#error_message').text(response.message);
            } else {
                $('#id_city').val(id_city);
                $('#title_en').val(response.data.title_en);
                $('#title_ar').val(response.data.title_ar);
                $('#delivery_cost').val(response.data.delivery_cost);
                $('#order_limit').val(response.data.order_limit);
                $('#governora').text(response.data.cityes.title_en);
            }
        }
    });
});
$(document).on('click', '#EditClient', function(e) {
    e.preventDefault();
    var data = {
        title_en: $('#title_en').val(),
        title_ar: $('#title_ar').val(),
        delivery_cost: $('#delivery_cost').val(),
        order_limit: $('#order_limit').val(),
        city_id: $('#city_id').val(),
    };
    // let formdata = new FormData($('#formeditadmin')[0]);
    var id_city = $('#id_city').val();
    console.log(data);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: '{{ url("admin/city/update") }}/' + id_city,
        data: data,
        dataType: false,
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
                $('#modalEditcity').modal('hide');
                table.ajax.reload();
            }
        }
    });
});

{{--$(document).on('click', '#Deletecity', function(e) {--}}
{{--    e.preventDefault();--}}
{{--    var id_city = $(this).data('id');--}}
{{--    $.ajaxSetup({--}}
{{--        headers: {--}}
{{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--        }--}}
{{--    });--}}
{{--    $.ajax({--}}
{{--        type: 'DELETE',--}}
{{--        url: '{{ url("admin/city/delete") }}/' + id_city,--}}
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

$(document).on('click', '#Deletecity', function(e) {
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
            url: '{{ url("admin/city/delete") }}/' + id,
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

$(document).on('click', '#status', function(e) {
    e.preventDefault();
    // console.log("Alliiiii");
    var edit_id = $(this).data('id');
    var status = $(this).data('viewing_status');
    if(status == 1){
        status = 0;
    }else{
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
        url: '{{ route("city.status") }}',
        data: data,
        success: function(response) {
            // $('#error_message').html("");
            // $('#error_message').addClass("alert alert-danger");
            // $('#error_message').text(response.message);
            table.ajax.reload();
        }
    });
});
$(document).on('click', '#statusoff', function(e) {
    e.preventDefault();
    // console.log("Alliiiii");
    var edit_id = $(this).data('id');
    var status = $(this).data('viewing_status');
    if(status == 1){
        status = 0;
    }else{
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
        url: '{{ route("city.status") }}',
        data: data,
        success: function(response) {
            // $('#error_message').html("");
            // $('#error_message').addClass("alert alert-danger");
            // $('#error_message').text(response.message);
            table.ajax.reload();
        }
    });
});
</script>
@endsection
