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
            {{ trans('menu.Scheduled_notifications') }}</span>
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
    <div class="modal" id="modalAddCategory">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('menu.Scheduled_notifications') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal"
                            type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <ul id="list_error_message"></ul>
                    <form id="formcategory" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.title') }} :</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.url') }} :</label>
                                <input type="url" class="form-control" id="url" name="url">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.body') }} :</label>
                                <textarea class="form-control" id="body" name="body" rows="3"
                                          required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"> {{ trans('ads.cat') }} :</label>
                                <select name="cat_id" class="form-control">
                                    <option value=""></option>
                                    @foreach($cat2 as $cat2)
                                        @if(App::getLocale() == 'en')
                                            <option value="{{ $cat2->id }}">{{ $cat2->title_en }}</option>
                                        @else
                                            <option value="{{ $cat2->id }}">{{ $cat2->title_ar }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"> {{ trans('clothes.Prodect') }} :</label>
                                <select name="pro_id" class="form-control pro2">
                                    <option value=""></option>
                                    @foreach($pro2 as $pro2)
                                        @if(App::getLocale() == 'en')
                                            <option value="{{ $pro2->id }}">{{ $pro2->title_en }}</option>
                                        @else
                                            <option value="{{ $pro2->id }}">{{ $pro2->title_ar }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"> {{ trans('clothes.Prodect') }} :</label>
                                <select name="multi_product_id[]" class="form-control pro2" multiple>
                                    <option value=""></option>
                                    @foreach($pro as $pro2)
                                        @if(App::getLocale() == 'en')
                                            <option value="{{ $pro2->id }}">{{ $pro2->title_en }}</option>
                                        @else
                                            <option value="{{ $pro2->id }}">{{ $pro2->title_ar }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.url') }} :</label>
                                <input type="datetime-local" class="form-control" id="send_at" name="send_at" required>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('menu.Scheduled_notifications') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal"
                            type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <ul id="list_error_message2"></ul>
                    <form id="formeditadmin" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" id="id_category">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.title') }} :</label>
                                <input type="text" class="form-control" id="titler" name="title" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.url') }} :</label>
                                <input type="url" class="form-control" id="urlr" name="url">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.body') }} :</label>
                                <textarea class="form-control" id="bodyr" name="body" rows="3"
                                          required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"> {{ trans('ads.cat') }} :</label>
                                <select name="cat_id" id="cat_id_selectize" class="form-control">
                                    <option value=""></option>
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
                                <label class="form-label"> {{ trans('clothes.Prodect') }} :</label>
                                <select name="pro_id" id="pro_id_selectize" class="form-control">
                                    <option value=""></option>
                                    @foreach($pro as $pro2)
                                        @if(App::getLocale() == 'en')
                                            <option value="{{ $pro2->id }}">{{ $pro2->title_en }}</option>
                                        @else
                                            <option value="{{ $pro2->id }}">{{ $pro2->title_ar }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label"> {{ trans('clothes.Prodect') }} :</label>
                                <select name="multi_product_id[]" class="form-control pro2" multiple>
                                    <option value=""></option>
                                    @foreach($pro as $pro)
                                        @if(App::getLocale() == 'en')
                                            <option value="{{ $pro->id }}">{{ $pro->title_en }}</option>
                                        @else
                                            <option value="{{ $pro->id }}">{{ $pro->title_ar }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('notification.url') }} :</label>
                                <input type="datetime-local" class="form-control" id="send_atr" name="send_at" required>
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
                    @can('notifications-create')
                        <div class="row row-xs wd-xl-80p">
                            <div class="col-sm-6 col-md-3 mg-t-10">
                                <button class="btn btn-info-gradient btn-block" id="ShowModalAddCategory">
                                    <a href="#"
                                       style="font-weight: bold; color: beige;">{{ trans('notification.add_not') }}</a>
                                </button>
                            </div>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        @can('notifications-view')
                            <table class="table table-hover" id="get_notifications" style=" text-align: center;">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{ trans('notification.title') }}</th>
                                    <th class="border-bottom-0">{{ trans('notification.repeat') }}</th>
                                    <th class="border-bottom-0">{{ trans('notification.body') }}</th>
                                    <th class="border-bottom-0">{{ trans('category.Status') }}</th>
                                    <th class="border-bottom-0">
                                        @canany([ 'notifications-update' , 'notifications-delete' ])
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>
        var local = "{{ App::getLocale() }}";
        var table = $('#get_notifications').DataTable({
            // processing: true,
            ajax: '{!! route("get_notification") !!}',
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
                        if (data.title) {
                            return data.title;
                        } else {
                            return "";
                        }
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.days ?? "";
                    },
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        if (data.message) {
                            return data.message;
                        } else {
                            return data.message;
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
                        @can('notifications-update')
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                        @endcan
                        @can('notifications-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.title}"><i class="las la-trash"></i></button>
                        @endcan
                        `;
                    },
                    orderable: false,
                    searchable: false
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
                url: '{{ route("add_notification") }}',
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
        // view modification data
        $(document).on('click', '#ShowModalEditCategory', function (e) {
            e.preventDefault();
            var id_category = $(this).data('id');
            $('#modalEditCategory').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("notification/edit") }}/' + id_category,
                data: "",
                success: function (response) {
                    $('#id_category').val(response.data.id);
                    $('#titler').val(response.data.title);
                    $('#send_atr').val(response.data.send_at);
                    $('#bodyr').val(response.data.message);
                    $('#urlr').val(response.data.url);

                    var $select = $("#cat_id_selectize").selectize();
                    var selectiz = $select[0].selectize;
                    if(response.data.categories){
                        if(local == 'en'){
                            selectiz.setValue(selectiz.search(response.data.categories.title_en).items[0].id);
                        }else{
                            selectiz.setValue(selectiz.search(response.data.categories.title_ar).items[0].id);
                        }
                    }

                    var $select2 = $("#pro_id_selectize").selectize();
                    var selectiz2 = $select2[0].selectize;
                    if(response.data.product){
                        if(local == 'en'){
                            selectiz2.setValue(selectiz2.search(response.data.product.title_en).items[0].id);
                        }else{
                            selectiz2.setValue(selectiz2.search(response.data.product.title_ar).items[0].id);
                        }
                    }
                }
            });
        });
        $(document).on('click', '#EditClient', function (e) {
            e.preventDefault();

            let formdata = new FormData($('#formeditadmin')[0]);
            var id_category = $('#id_category').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("notification/update") }}/' + id_category,
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

        {{--$(document).on('click', '#DeleteCategory', function (e) {--}}
        {{--    e.preventDefault();--}}
        {{--    var id_category = $(this).data('id');--}}
        {{--    $.ajaxSetup({--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        }--}}
        {{--    });--}}
        {{--    $.ajax({--}}
        {{--        type: 'DELETE',--}}
        {{--        url: '{{ url("admin/category/delete") }}/' + id_category,--}}
        {{--        data: '',--}}
        {{--        contentType: false,--}}
        {{--        processData: false,--}}
        {{--        success: function (response) {--}}
        {{--            $('#error_message').html("");--}}
        {{--            $('#error_message').addClass("alert alert-danger");--}}
        {{--            $('#error_message').text(response.message);--}}
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
                    url: '{{ url("notification/delete") }}/' + id,
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
                url: '{{ url("notification/statuss") }}',
                data: data,
                success: function (response) {
                    // $('#error_message').html("");
                    // $('#error_message').addClass("alert alert-danger");
                    // $('#error_message').text(response.message);
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
                url: '{{ url("notification/statuss") }}',
                data: data,
                success: function (response) {
                    // $('#error_message').html("");
                    // $('#error_message').addClass("alert alert-danger");
                    // $('#error_message').text(response.message);
                    table.ajax.reload();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function (){
            $('#pro_id_selectize').selectize({
                sortField: 'text'
            });
            $('#cat_id_selectize').selectize({
                sortField: 'text'
            });
            $('.pro2').selectize({
                sortField: 'text'
            });
        });
    </script>
@endsection
