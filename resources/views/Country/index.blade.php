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
                <h4 class="content-title mb-0 my-auto">{{ trans('ads.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('ads.title_country') }}</span>
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
        <div class="modal" id="modalAddCountry">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('ads.title_country') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAds" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.name_en') }} :</label>
                                    <input type="text" class="form-control" name="title_en" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.name_ar') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.coin_name') }} :</label>
                                    <input type="text" class="form-control" name="coin_name" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.coin_name_en') }} :</label>
                                    <input type="text" class="form-control" name="coin_name_en" required>
                                </div>
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.coin_price') }} :</label>-->
                                <!--    <input type="number" class="form-control" name="coin_price" required>-->
                                <!--</div>-->
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.phone_code') }} :</label>
                                    <input type="number" class="form-control" name="phone_code" required>
                                </div>
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.regus') }} :</label>-->
                                <!--    <input type="text" class="form-control" name="regus" required>-->
                                <!--</div>-->
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.first_kg') }} :</label>-->
                                <!--    <input type="number" class="form-control" name="first_kg"  >-->
                                <!--</div>-->
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.after_first_kg') }} :</label>-->
                                <!--    <input type="number" class="form-control" name="after_first_kg"  >-->
                                <!--</div>-->
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.weight') }} :</label>-->
                                <!--    <input type="number" class="form-control" name="weight"  >-->
                                <!--</div>-->
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.models') }} :</label>
                                    <input type="file" required class="form-control" name="image">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success AddCountry"
                                        id="AddCountry">{{ trans('category.Save') }}</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ trans('category.Close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
        <div class="modal" id="modalEditCountry">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ trans('ads.title_country') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal"
                                type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="formeditadmin" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" id="id_country">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.name_en') }} :</label>
                                    <input type="text" class="form-control" name="title_en" id="title_en" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.name_ar') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" id="title_ar" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.coin_name') }} :</label>
                                    <input type="text" class="form-control" name="coin_name"  id="coin_name" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.coin_name_en') }} :</label>
                                    <input type="text" class="form-control" name="coin_name_en"  id="coin_name_en" required>
                                </div>
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.coin_price') }} :</label>-->
                                <!--    <input type="number" class="form-control" name="coin_price" id="coin_price" required>-->
                                <!--</div>-->
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.phone_code') }} :</label>
                                    <input type="number" class="form-control" name="phone_code"  id="phone_code" required>
                                </div>
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.regus') }} :</label>-->
                                <!--    <input type="text" class="form-control" name="regus"  id="regus" required>-->
                                <!--</div>-->
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.first_kg') }} :</label>-->
                                <!--    <input type="text" class="form-control" name="first_kg" id="first_kg" required>-->
                                <!--</div>-->
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.after_first_kg') }} :</label>-->
                                <!--    <input type="text" class="form-control" name="after_first_kg" id="after_first_kg" >-->
                                <!--</div>-->
                                <!--<div class="form-group col-md-12">-->
                                <!--    <label for="exampleInputEmail1">{{ trans('ads.weight') }} :</label>-->
                                <!--    <input type="text" class="form-control" name="weight"  id="weight">-->
                                <!--</div>-->
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('ads.models') }} :</label>
                                    <input type="file" required class="form-control" name="image" id="image">
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
                        @can('region-create')
                            <div class="row row-xs wd-xl-80p">
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block" id="ShowModalAddCountry">
                                        <a href="#" style="font-weight: bold; color: beige;">{{ trans('ads.add_country') }}</a>
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('region-view')
                                <table class="table table-hover" id="get_country" style=" text-align: center;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ trans('app_users.name') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.coin_name') }}</th>
                                        <!--<th class="border-bottom-0">{{ trans('ads.coin_price') }}</th>-->
                                        <th class="border-bottom-0">{{ trans('ads.models') }}</th>
                                        <th class="border-bottom-0">{{ trans('ads.Status') }}</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
            integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>
        var local = "{{ App::getLocale() }}";
        var table = $('#get_country').DataTable({
            // processing: true,
            ajax: '{!! route("get_country") !!}',
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
                    render: function (data, row, type) {
                        if(local == 'en') {
                            return data.title_en;
                        } else {
                            return data.title_ar;
                        }
                    },
                },
                {
                    'data': 'coin_name',
                    'className': 'text-center text-lg text-medium'
                },
                // {
                //     'data': 'coin_price',
                //     'className': 'text-center text-lg text-medium'
                // },
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
                    'data': null,
                    render: function(data, row, type) {
                        var phone;
                        if (data.status == '0') {
                            return `<button class="btn btn-danger-gradient btn-block" id="statusoff" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                        } else {
                            return `<button class="btn btn-success-gradient btn-block" id="status" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;
                        }
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {

                            if(data.id == 3) {
                                return `
                        @can('region-update')
                                <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCountry" data-id="${data.id}"><i class="las la-pen"></i></button>
                        @endcan

                                `;

                            } else {
                                return `
                        @can('region-update')
                                <button class="modal-effect btn btn-sm btn-info" id="ShowModalEditCountry" data-id="${data.id}"><i class="las la-pen"></i></button>
                        @endcan

                                @can('region-delete')
                                <button class="modal-effect btn btn-sm btn-danger" id="DeleteAds" data-id="${data.id}" data-namee="${data.title_en ?? ''}"><i class="las la-trash"></i></button>
                        @endcan
                                `;
                            }


                    },
                },
            ],
        });
        //  view modal Ads
        $(document).on('click', '#ShowModalAddCountry', function (e) {
            e.preventDefault();
            $('#modalAddCountry').modal('show');
        });
        // // Ads admin
        $(document).on('click', '.AddCountry', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formAds')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("add_country") }}',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log("Done");
                    $('#AddCountry').text('Saving');
                    $('#error_message').html("");
                    $('#error_message').addClass("alert alert-info");
                    $('#error_message').text(response.message);
                    $('#modalAddCountry').modal('hide');
                    $('#formAds')[0].reset();
                    table.ajax.reload();
                }
            });
        });
        // // view modification data
        $(document).on('click', '#ShowModalEditCountry', function (e) {
            e.preventDefault();
            var id_country = $(this).data('id');
            $('#modalEditCountry').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("admin/country/edit") }}/' + id_country,
                data: "",
                success: function (response) {
                    console.log(response);
                    if (response.status == 404) {
                        console.log('error');
                        $('#error_message').html("");
                        $('#error_message').addClass("alert alert-danger");
                        $('#error_message').text(response.message);
                    } else {

                        $('#id_country').val(id_country);
                        $('#title_en').val(response.data.title_en);
                        $('#title_ar').val(response.data.title_ar);
                        $('#coin_price').val(response.data.coin_price);
                        $('#phone_code').val(response.data.phone_code);
                        $('#regus').val(response.data.regus);
                        $('#coin_name').val(response.data.coin_name);
                        $('#coin_name_en').val(response.data.coin_name_en);
                        $('#image').val(response.data.image);
                        $('#first_kg').val(response.data.first_kg);
                        $('#after_first_kg').val(response.data.after_first_kg);
                        $('#weight').val(response.data.weight);
                    }
                }
            });
        });
        $(document).on('click', '#EditClient', function (e) {
            e.preventDefault();
            let formdata = new FormData($('#formeditadmin')[0]);
            var id_country = $('#id_country').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/country/update") }}/' + id_country,
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
                        $('#modalEditCountry').modal('hide');
                        table.ajax.reload();
                    }
                }
            });
        });
        $(document).on('click', '#DeleteAds', function (e) {
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
                    url: '{{ url("admin/country/delete") }}/' + id,
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
                url: '{{ route("country.status") }}',
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
                url: '{{ route("country.status") }}',
                data: data,
                success: function (response) {
                    table.ajax.reload();
                }
            });
        });
    </script>
@endsection
