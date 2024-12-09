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
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('category.content_titlegg') }}</span>
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
        <div class="row row-sm">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mg-b-20">
                            <div class="parsley-input col-md-3" id="fnWrapper">
                                <label for="exampleInputEmail1">{{ trans('orders.phone') }} :</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"
                                id="s">{{ trans('orders.Sarech') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card mg-b-20">
                    <div class="card-header pb-0">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('categories-view')
                                <table class="table table-hover" id="get_categories" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">{{ trans('category.Mobile') }}</th>
                                        <th class="border-bottom-0">{{ trans('category.gate') }}</th>
                                        <th class="border-bottom-0">{{ trans('category.Message') }}</th>
                                        <th class="border-bottom-0">{{ trans('category.Status') }}</th>
                                        <th class="border-bottom-0">{{ trans('category.reply') }}</th>
                                        <th class="border-bottom-0">{{ trans('contact.Created_at') }}</th>
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
    <script>
        var local = "{{ App::getLocale() }}";
        $('#s').click(function (e) {
            e.preventDefault();
            var phone = $('#phone').val();
            $('#get_categories').DataTable({
                ordering: false,
                bDestroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 20,
                ajax: {
                    url: '{{ url("admin/archives/get") }}' + '/?phone=' + phone,
                    cache: true
                },
                lengthMenu: [
                    [10, 50 , 200 , 500 , 1000 ,  -1],
                    [10, 50 , 200 , 500 , 1000],
                ],
                columns: [
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return data.numbers ?? "";
                        }
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return data.gateway ?? "";
                        }
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return `<textarea class="form-control" disabled style="width:400px; display:inline;">${data.message}</textarea>`;
                        },
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            if (data.status == 'success') {
                                return `<button class="btn btn-success-gradient btn-block" id="status">{{ trans('category.Active') }}</button>`;
                            } else {
                                return `<button class="btn btn-danger-gradient btn-block" id="statusoff">{{ trans('category.iActive') }}</button>`;
                            }
                        },
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return data.gate_message ?? "";
                        }
                    },
                    {
                        'data': 'created_at',
                        'className': 'text-center text-lg text-medium',
                        render: function (data) {
                            if (data == null) return "";
                            var date = new Date(data);
                            var month = date.getMonth() + 1;
                            return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                                month) + "/" + date.getFullYear();
                        }
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return `
                @can('categories-delete')
                            <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.numbers}"><i class="las la-trash"></i></button>
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
            pageLength: 20,
            ajax: '{!! route("get_archives") !!}',
            columns: [
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.numbers ?? "";
                    }
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.gateway ?? "";
                    }
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return `<textarea class="form-control" disabled style="width:400px; display:inline;">${data.message}</textarea>`;
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (data.status == 'success') {
                            return `<button class="btn btn-success-gradient btn-block" id="status">{{ trans('category.Active') }}</button>`;
                        } else {
                            return `<button class="btn btn-danger-gradient btn-block" id="statusoff">{{ trans('category.iActive') }}</button>`;
                        }
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.gate_message ?? "";
                    }
                },
                {
                    'data': 'created_at',
                    'className': 'text-center text-lg text-medium',
                    render: function (data) {
                        if (data == null) return "";
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                            month) + "/" + date.getFullYear();
                    }
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return `
                @can('categories-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.numbers}"><i class="las la-trash"></i></button>
                @endcan
                        `;
                    },
                },
            ],
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
                    url: '{{ url("admin/archives/delete") }}/' + id,
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
    </script>

@endsection
