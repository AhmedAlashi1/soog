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

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('ads.home') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('ads.content_title') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div id="error_message"></div>
    <div class="main-body">
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
        <div class="modal" id="modalAddAds">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('ads.content_title') }}</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAds" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.url') }} :</label>
                                    <input type="text" class="form-control" name="url" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.lauout_title') }} :</label>

                                    {{--                            <select name="lauout_title" class="form-control" >--}}
                                    <select name="layout" class="form-control"   >
                                        <option value="1">اعلى الرئيسيه</option>
                                        <option value="2">تجارية</option>
                                        {{--                                <option value="6">صفحة اتمام الطلب</option>--}}
                                        {{--                                <option value="5">صفحة الاقسام</option>--}}
                                        {{--                                <option value="2">اسفل الرئيسيه</option>--}}
                                        {{--                                <option value="4">اعلى الرئيسيه قديم</option>--}}
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
                                    <label class="form-label"> الدولة :</label>
                                    <select name="country_id"  class="form-control">
                                        <option value=""></option>
                                        {!! $getCountry  !!}
                                    </select>
                                </div>

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
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddAds" id="AddAds">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
        <div class="modal" id="modalEditAds">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Ads</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formeditadmin" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="id_Ads">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.title') }} :</label>
                                    <input type="text" class="form-control" name="title" id="title" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.url') }} :</label>
                                    <input type="text" class="form-control" name="url" id="url" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.lauout_title') }} :</label>
                                    <select name="layout" class="form-control" id="lauout_titledsdsds" >
                                        <option value="1">اعلى الرئيسيه</option>
                                        <option value="2">تجارية</option>
                                    </select>
                                </div>
                                {{--                            <div class="form-group col-md-12">--}}
                                {{--                                <label for="exampleInputEmail1">{{ trans('ads.lauout_title') }} :</label>--}}
                                {{--                                <input type="text" class="form-control" name="lauout_title" id="lauout_title" required>--}}
                                {{--                            </div>--}}
                                {{--                            <div class="form-group col-md-12">--}}
                                {{--                                <label for="exampleInputEmail1">{{ trans('ads.layout') }} :</label>--}}
                                {{--                                <input type="number" class="form-control" name="layout" id="layout" required>--}}
                                {{--                            </div>--}}
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.days') }} :</label>
                                    <input type="number" class="form-control" name="days" id="days" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.cost') }} :</label>
                                    <input type="number" class="form-control" name="cost" id="cost" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="form-label"> الدولة :</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value=""></option>
                                        {!! $getCountry  !!}
                                    </select>
                                </div>
                                {{--                            <div class="form-group col-md-12">--}}
                                {{--                                <label class="form-label">{{ trans('ads.cat') }} :</label>--}}
                                {{--                                <select name="cat_id" id="cat_id" class="form-control">--}}
                                {{--                                    <option value=""></option>--}}
                                {{--                                    {!! $getSubCat  !!}--}}
                                {{--                                    @foreach($category as $cat)--}}
                                {{--                                        @if(App::getLocale() == 'en')--}}
                                {{--                                            <option value="{{ $cat->id }}">{{ $cat->title_en }}</option>--}}
                                {{--                                        @else--}}
                                {{--                                            <option value="{{ $cat->id }}">{{ $cat->title_ar }}</option>--}}
                                {{--                                        @endif--}}
                                {{--                                    @endforeach--}}
                                {{--                                </select>--}}
                                {{--                            </div>--}}
                                {{--                            <div class="form-group col-md-12">--}}
                                {{--                                <label class="form-label">{{ trans('ads.prodect') }} :</label>--}}
                                {{--                                <select name="product_id" id="product_id" class="form-control">--}}
                                {{--                                    <option value=""></option>--}}
                                {{--                                    @foreach($prodec as $prodect)--}}
                                {{--                                        @if(App::getLocale() == 'en')--}}
                                {{--                                            <option value="{{ $prodect->id }}">{{ $prodect->title_en }}</option>--}}
                                {{--                                        @else--}}
                                {{--                                            <option value="{{ $prodect->id }}">{{ $prodect->title_ar }}</option>--}}
                                {{--                                        @endif--}}
                                {{--                                    @endforeach--}}
                                {{--                                </select>--}}
                                {{--                            </div>--}}
                                {{--                            <div class="form-group col-md-12">--}}
                                {{--                                <label class="form-label">{{ trans('ads.prodect') }} :</label>--}}
                                {{--                                <select name="multi_product_id[]" id="multi_product_id" class="form-control" multiple>--}}
                                {{--                                    <option value=""></option>--}}
                                {{--                                    @foreach($prodec as $prodect)--}}
                                {{--                                        @if(App::getLocale() == 'en')--}}
                                {{--                                            <option value="{{ $prodect->id }}">{{ $prodect->title_en }}</option>--}}
                                {{--                                        @else--}}
                                {{--                                            <option value="{{ $prodect->id }}">{{ $prodect->title_ar }}</option>--}}
                                {{--                                        @endif--}}
                                {{--                                    @endforeach--}}
                                {{--                                </select>--}}
                                {{--                            </div>--}}
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.models') }}  :</label>
                                    <input type="file" class="form-control" name="image" id="image" required>
                                </div>
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
                        @can('ads-create')
                            <div class="row row-xs wd-xl-80p">
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="modal-effect btn btn-info-gradient btn-block"  id="ShowModalAddAds" >
                                        {{ trans('ads.add') }}
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('ads-view')
                                <table class="table table-hover" id="get_Ads" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ trans('category.Image') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.url') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.lauout_title') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.days') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.cost') }}</th>
                                        {{--                                    <th class="border-bottom-0">{{ trans('ads.sum') }}</th>--}}
                                        {{--                                    <th class="border-bottom-0">{{ trans('clothes.international') }}</th>--}}
                                        <th class="border-bottom-0">{{ trans('ads.Status') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.Created_at') }}</th>
                                        <th class="border-bottom-0">
                                            @canany([ 'ads-update' , 'ads-delete' ])
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>
        var local = "{{ App::getLocale() }}";
        var type = "{{ request()->get('type') }}";
        var table = $('#get_Ads').DataTable({
            // processing: true,
            ajax: '{!! route("get_ads") !!}',
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
                    render: function(data, row, type) {
                        if (data.image) {
                            return `<img src="{{url('/assets/tmp/')}}/${data.image}" style="width: 40px;height: 40px">`;
                        } else {
                            return "No Image";
                        }
                    },
                },
                {
                    'data': 'url',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': 'lauout_title',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': 'days',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': 'cost',
                    'className': 'text-center text-lg text-medium'
                },
                // {
                //     'data': null,
                //     render: function(data, row, type) {
                //         var diffInDays =  moment().diff(data.created_at, "days");
                //         var sum = diffInDays * data.cost;
                //         return sum ?? "";
                //     },
                // },
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
                @can('ads-update')
                        <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditAds" data-id="${data.id}"><i class="las la-pen"></i></button>
                @endcan
                        @can('ads-delete')
                        <button class="modal-effect btn btn-sm btn-danger" id="DeleteAds" data-id="${data.id}" data-namee="${data.url ?? ''}"><i class="las la-trash"></i></button>
                @endcan
                        `;
                    },
                    orderable: false,
                    searchable: false
                },
            ],
        });
        //  view modal Ads
        $(document).on('click', '#ShowModalAddAds', function(e) {
            e.preventDefault();
            $('#modalAddAds').modal('show');
        });
        // // Ads admin
        $(document).on('click', '.AddAds', function(e) {
            e.preventDefault();
            let formdata = new FormData($('#formAds')[0]);
            // console.log(formdata);
            // console.log("formdata");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("add_ads") }}',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(response) {
                    // console.log("Done");
                    $('#AddAds').text('Saving');
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-info");
                    $('#error_message').text(response.message);
                    $('#modalAddAds').modal('hide');
                    $('#formAds')[0].reset();
                    table.ajax.reload();
                }
            });
        });
        // // view modification data
        $(document).on('click', '#ShowModalEditAds', function(e) {
            e.preventDefault();
            var id_Ads = $(this).data('id');
            $('#modalEditAds').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("admin/ads/edit") }}/' + id_Ads,
                data: "",
                success: function(response) {
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {

                        $('#id_Ads').val(id_Ads);
                        $('#title').val(response.data.title);
                        $('#url').val(response.data.url);
                        $('#layout').val(response.data.layout);
                        $('#days').val(response.data.days);
                        $('#cost').val(response.data.cost);
                        if (response.data.country_id){
                            $("select[id=country_id]  option[value="+response.data.country_id+"]").attr("selected", "selected");
                        }else {
                            $("select[id=country_id]  option[value='']").attr("selected", "selected");

                        }

                        $("select[name=layout]  option[value="+response.data.layout+"]").attr("selected", "selected");
                        table.ajax.reload();
                        // $("select [name='country_id'] option[value='1']").attr("selected", "selected");
                        // if (response.data.layout == '1') {
                        //     $("select[name=layout] option[value='1']").attr("selected", "selected");
                        // } else {
                        //     $("select[name=layout] option[value='2']").attr("selected", "selected");
                        // }



                    }
                }
            });
        });

        $(document).on('click', '#EditClient', function(e) {
            e.preventDefault();
            // var data = {
            //     url: $('#url').val(),
            //     layout: $('#layout').val(),
            //     lauout_title: $('#lauout_title').val(),
            //     days: $('#days').val(),
            //     cost: $('#cost').val(),
            //     image: $('#image').val(),
            //     status: $('#status').val(),
            //     cat_id: $('#cat_id ').val(),
            //     product_id: $('#product_id  ').val(),
            // };
            let formdata = new FormData($('#formeditadmin')[0]);
            var id_Ads = $('#id_Ads').val();
            // console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/ads/update") }}/' + id_Ads,
                data: formdata,
                contentType: false,
                processData: false,
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
                        $('#modalEditAds').modal('hide');
                        table.ajax.reload();
                    }
                }
            });
        });

        {{--$(document).on('click', '#DeleteAds', function(e) {--}}
        {{--    e.preventDefault();--}}
        {{--    var id_Ads = $(this).data('id');--}}
        {{--    $.ajaxSetup({--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        }--}}
        {{--    });--}}
        {{--    $.ajax({--}}
        {{--        type: 'DELETE',--}}
        {{--        url: '{{ url("admin/ads/delete") }}/' + id_Ads,--}}
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

        $(document).on('click', '#DeleteAds', function(e) {
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
                    url: '{{ url("admin/ads/delete") }}/' + id,
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
                url: '{{ route("ads.status") }}',
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
                url: '{{ route("ads.status") }}',
                data: data,
                success: function(response) {
                    // $('#error_message').html("");
                    // $('#error_message').addClass("alert alert-danger");
                    // $('#error_message').text(response.message);
                    table.ajax.reload();
                }
            });
        });
        $(document).on('click', '.international', function (e) {
            e.preventDefault();
            var edit_id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("ads/update/status") }}'+'?typeint=1&id='+edit_id,
                data: "",
                success: function (response) {

                    table.ajax.reload();
                }
            });
        });

        $(document).ready(function () {

            $('#product_id').selectize({
                sortField: 'text'
            });
            $('#cat_id').selectize({
                sortField: 'text'
            });
            $('#multi_product_id').selectize({
                sortField: 'text'
            });
            $('#add_multi_product_id').selectize({
                sortField: 'text'
            });
            $('#Add_product_id').selectize({
                sortField: 'text'
            });
            $('#Add_cat_id').selectize({
                sortField: 'text'
            });
            $('#lauout_titledsdsds').selectize.addOption({value:13,text:'foo'});
            $('#lauout_titledsdsds').selectize.addItem(13);

        });
    </script>
@endsection
