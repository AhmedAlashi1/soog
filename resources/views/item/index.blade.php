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


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> /
             ادارة الاصناف</span>
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
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">ادارة التصنيفات </h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                                                  type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message"></ul>
                        <form id="formcategory" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_A') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_E') }} :</label>
                                    <input type="text" class="form-control" name="title_en" required>
                                </div>


                                <div class="form-group col-md-12">
                                    <label class="form-label">نوع التصنيف :</label>
                                    <select name="type" class="form-control">
                                        <option value="1">الماركات</option>
                                        <option value="2">المراحل التعليمية</option>
                                        <option value="3">التخصصات التعليمية</option>
                                        <option value="4">المواد التعليمية</option>
                                        <option value="5">انواع الحيوانات</option>
                                        <option value="6">تصنيفات الموضة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddCategory" id="AddCategory">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('category.Close') }}</button>
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
                        <h6 class="modal-title">{{ trans('category.content_title') }}</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                                                  type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message2"></ul>
                        <form id="formeditadmin" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="id_category">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_A') }} :</label>
                                    <input type="text" class="form-control" id="title_ar" name="title_ar" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_E') }} :</label>
                                    <input type="text" class="form-control" id="title_en" name="title_en" required>
                                </div>


                                <div class="form-group col-md-12">
                                    <label class="form-label">نوع التصنيف :</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="1">الماركات</option>
                                        <option value="2">المراحل التعليمية</option>
                                        <option value="3">التخصصات التعليمية</option>
                                        <option value="4">المواد التعليمية</option>
                                        <option value="5">انواع الحيوانات</option>
                                        <option value="6">تصنيفات الموضة</option>
                                    </select>
                                </div>

{{--                                <div class="form-group col-md-12">--}}
{{--                                    <label class="form-label">{{ trans('category.Status') }} :</label>--}}
{{--                                    <select name="status" id="status" class="form-control">--}}
{{--                                        <option value="1">{{ trans('category.Active') }}</option>--}}
{{--                                        <option value="0">{{ trans('category.iActive') }}</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="EditClient">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('category.Close') }}</button>
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
                        @can('categories-create')
                            <div class="row row-xs wd-xl-80p">
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block" id="ShowModalAddCategory">
                                        <a href="#" style="font-weight: bold; color: beige;">اضافة صنف</a>
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
                                        <th class="border-bottom-0">#</th>
{{--                                        <th class="border-bottom-0">{{ trans('category.TitleM') }}</th>--}}
                                        <th class="border-bottom-0">اسم التصنيف</th>
                                        {{--                                <th class="border-bottom-0">{{ trans('category.Description') }}</th>--}}
                                        <th class="border-bottom-0">نوع التصنيف</th>
{{--                                        <th class="border-bottom-0">{{ trans('clothes.international') }}</th>--}}
{{--                                        <th class="border-bottom-0">{{ trans('clothes.appear') }}</th>--}}
{{--                                        <th class="border-bottom-0">{{ trans('category.Status') }}</th>--}}
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
{{--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
{{--    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

{{--    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>--}}
    <script>
        var local = "{{ App::getLocale() }}";
        var id = "{{ request()->route('id') }}";
        var table = $('#get_categories').DataTable({
            // processing: true,
            ajax: '{!! route("get_item") !!}',
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
                // {
                //     'data': null,
                //     'className': 'text-center text-lg text-medium',
                //     render: function(data, row, type) {
                //         return 'قسم رائيسي'
                //         if (local == "en") {
                //             return data.title_en ?? "";
                //         }
                //         else {
                //             return data.title_ar ?? "";
                //         }
                //     }
                // },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function(data, row, type) {
                        if (local == "en") {
                            return  data.title_en;
                    {{--        return `<a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/categories/show') }}/${data.id}" >--}}
                    {{--${data.title_en}</a>`;--}}
                        } else {
                            return   data.title_ar;

                    {{--        return `<a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/categories/show') }}/${data.id}" >--}}
                    {{--${data.title_ar}</a>`;--}}
                        }
                    },
                },

                {
                    'data': null,
                    render: function(data, row, type) {

                        if (data.type == '1' )  {
                            return 'الماركات'
                        } else if(data.type == '2' )   {
                            return 'المراحل التعليمية'
                        } else if(data.type == '3' )   {
                            return 'التخصصات التعليمية'
                        } else if(data.type == '4' )   {
                            return 'المواد التعليمية'
                        } else if(data.type == '5' )   {
                            return 'انواع الحيوانات'
                        } else if(data.type == '6' )   {
                            return 'تصنيفات الموضة'
                        }
                    },
                },
                // {
                //     'data': null,
                //     render: function (data, row, type) {
                //         if (data.international == '1') {
                //             return `<button class="modal-effect btn btn-sm btn-success international" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                //         } else {
                //             return `<button class="modal-effect btn btn-sm btn-danger  international" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                //         }
                //     },
                // },
                // {
                //     'data': null,
                //     render: function(data, row, type) {
                //         if (data.home == '1') {
                //             return `<button class="modal-effect btn btn-sm btn-success home" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                //         } else {
                //             return `<button class="modal-effect btn btn-sm btn-danger  home" data-id="${data.id}"><i class=" icon-check"></i></button>`;
                //         }
                //     },
                // },
                {{--{--}}
                {{--    'data': null,--}}
                {{--    render: function(data, row, type) {--}}
                {{--        var phone;--}}
                {{--        if (data.status == '1') {--}}
                {{--            return `<button class="btn btn-success-gradient btn-block" id="status" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;--}}
                {{--        } else {--}}
                {{--            return `<button class="btn btn-danger-gradient btn-block" id="statusoff" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;--}}
                {{--        }--}}
                {{--    },--}}
                {{--},--}}
                {
                    'data': 'created_at',
                    'className': 'text-center text-lg text-medium',
                    render: function(data) {
                        if (data == null) return "";
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" +
                            month) + "/" + date.getFullYear();
                    }
                },
                {
                    'data': null,
                    render: function(data, row, type) {
                        return `
{{--                @can('product-view')--}}
{{--                        <a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/clothes') }}/${data.id}" class="btn btn-success btn-sm" title="الاصناف"><i class="fa fa-clipboard"></i> {{ trans('category.Prodects') }}</a>--}}
{{--                @endcan--}}
                        @can('categories-update')
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCategory" data-id="${data.id}"><i class="las la-pen"></i></button>
                @endcan
                        @can('categories-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteCategory" data-id="${data.id}" data-namee="${data.title_ar}"><i class="las la-trash"></i></button>
                @endcan
                        `;
                    },
                    orderable: false,
                    searchable: false
                },
            ],
        });

        $( "#get_categories" ).sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                sendOrderToServer();
            }
        });

        //  view modal Category
        $(document).on('click', '#ShowModalAddCategory', function(e) {
            e.preventDefault();
            // jQuery.noConflict();
            $('#modalAddCategory').modal('show');
        });
        // Category admin
        $(document).on('click', '.AddCategory', function(e) {
            e.preventDefault();
            let formdata = new FormData($('#formcategory')[0]);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("add_item") }}',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(response) {
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
                        // jQuery.noConflict();
                        $('#modalAddCategory').modal('hide');
                        $('#formcategory')[0].reset();
                        table.ajax.reload();
                    }

                }
            });
        });
        // view modification data
        $(document).on('click', '#ShowModalEditCategory', function(e) {
            e.preventDefault();
            var id_category = $(this).data('id');
            // jQuery.noConflict();
            $('#modalEditCategory').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("admin/item/edit") }}/' + id_category,
                data: "",
                success: function(response) {
                    console.log(response);
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {
                        $('#id_category').val(id_category);
                        $('#title_en').val(response.data.title_en);
                        $('#title_ar').val(response.data.title_ar);

                        if (response.data.type == '1') {
                            $("select option[value='1']").attr("selected", "selected");
                        } else if (response.data.type == '2') {
                            $("select option[value='2']").attr("selected", "selected");
                        } else if (response.data.type == '3') {
                            $("select option[value='3']").attr("selected", "selected");
                        } else if (response.data.type == '4') {
                            $("select option[value='4']").attr("selected", "selected");
                        } else if (response.data.type == '5') {
                            $("select option[value='5']").attr("selected", "selected");
                        } else if (response.data.type == '6') {
                            $("select option[value='6']").attr("selected", "selected");
                        }
                    }
                }
            });
        });
        $(document).on('click', '#EditClient', function(e) {
            e.preventDefault();
            var data = {
                title_en: $('#title_en').val(),
                title_ar: $('#title_ar').val(),
                // description_en: $('#description_en').val(),
                // description_ar: $('#description_ar').val(),
                // image: $('#image').val(),
                type: $('#type').val(),
            };
            let formdata = new FormData($('#formeditadmin')[0]);
            var id_category = $('#id_category').val();
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/item/update") }}/' + id_category,
                data: formdata,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 400) {
                        // errors
                        $('#list_error_message2').html("");
                        $('#list_error_message2').addClass("alert alert-danger");
                        $.each(response.errors, function(key, error_value) {
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
                        // jQuery.noConflict();
                        $('#modalEditCategory').modal('hide');
                        table.ajax.reload();
                    }
                }
            });
        });
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
                url: '{{ route("update.status") }}',
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
                url: '{{ route("update.status") }}',
                data: data,
                success: function(response) {
                    // $('#error_message').html("");
                    // $('#error_message').addClass("alert alert-danger");
                    // $('#error_message').text(response.message);
                    table.ajax.reload();
                }
            });
        });
        $(document).on('click', '#DeleteCategory', function(e) {
            e.preventDefault();
            $('#usernamed').val($(this).data('namee'));
            var id_admin = $(this).data('id');
            // jQuery.noConflict();
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
                    url: '{{ url("admin/item/delete") }}/' + id,
                    data: '',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                        // jQuery.noConflict();
                        $('#modaldemo8').modal('hide');
                        table.ajax.reload();
                    }
                });
            });
        };
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
                url: '{{ url("category/update/status") }}'+'?typeint=1&id='+edit_id,
                data: "",
                success: function (response) {
                    table.ajax.reload();
                }
            });
        });
        $(document).on('click', '.home', function (e) {
            e.preventDefault();
            var edit_id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("category/update/status") }}'+'?typeint=2&id='+edit_id,
                data: "",
                success: function (response) {
                    table.ajax.reload();
                }
            });
        });
    </script>

@endsection
