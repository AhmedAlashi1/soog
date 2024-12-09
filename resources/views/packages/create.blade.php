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

    <link href="{{URL::asset('assets/plugins/prism/prism.css')}}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{URL::asset('assets/plugins/inputtags/inputtags.css')}}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span
                        class="text-muted mt-1 tx-13 mr-2 mb-0"> /
            {{ trans('packages.add')}}</span>
            </div>

        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div id="error_message"></div>
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    @if(Session::has('warning'))
                        <div class="alert alert-warning">
                            {{Session::get('warning')}}
                        </div>
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form id="formeditadmin" action="{{ route('packages.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{$type}}" name="type">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_title') }}</label>
                                <input type="text" class="form-control" id="title" name="title_ar" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_title_en') }}</label>
                                <input type="text" class="form-control" id="url" name="title_en">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_description') }}</label>
                                <textarea class="form-control" id="body" name="describe_ar" rows="4" required></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_description_en') }}</label>
                                <textarea class="form-control" id="body" name="describe_en" rows="4" required></textarea>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">{{ trans('packages.discount_note') }}</label>
                                <input type="text" class="form-control" id="title" name="note_ar" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">{{ trans('packages.discount_note_en') }}</label>
                                <input type="text" class="form-control" id="url" name="note_en">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">{{ trans('packages.price') }}</label>
                                <input type="number" class="form-control" id="price" name="price">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">{{ trans('packages.Term') }}</label>

                                <input type="number" class="form-control" id="days" name="days">
                            </div>
                            <div class="form-group col-md-12">
                            <h5>
                                {{ trans('packages.additional_data') }}
                            </h5>
                                <hr>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_title') }}</label>
                                <input type="text" class="form-control" id="title" name="internal_title_ar" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_title_en') }}</label>
                                <input type="text" class="form-control" id="url" name="internal_title_en">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_description') }}</label>
                                <textarea class="form-control" id="body" name="internal_describe_ar" rows="4" required></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_description_en') }}</label>
                                <textarea class="form-control" id="body" name="internal_describe_en" rows="4" required></textarea>
                            </div>


                            <div class="form-group col-md-6"  >
                                <div class="text-wrap">
                                    <div class="example">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ trans('packages.points') }}</label>

                                            <input type="text" id="testetset" name="internal_point_ar[]" data-role="tagsinput"
                                                   class="form-control sr-only"  ></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6"  >
                                <div class="text-wrap">
                                    <div class="example">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ trans('packages.points_en') }}</label>

                                            <input type="text" id="testetset" name="internal_point_en[]" data-role="tagsinput"
                                                   class="form-control sr-only"  ></div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    id="EditClient">{{ trans('notification.sendt') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            @can('customer-view')
                <div class="row row-sm" hidden="hidden" id="hiddenFilter">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="get">
                                    <div class="row mg-b-20">
                                        <div class="parsley-input col-md-3" id="fnWrapper">
                                            <label for="exampleInputEmail1">{{ trans('app_users.name') }} :</label>
                                            <input type="text" class="form-control" id="named" name="name">
                                        </div>
                                        <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                            <label>{{trans('orders.phone')}} :</label>
                                            <input type="number" class="form-control form-control-md mg-b-20"
                                                   name="phone"
                                                   id="phoned">
                                        </div>
                                        <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                            <label>{{trans('app_users.charged_balance')}} :</label>
                                            <input type="number" class="form-control form-control-md mg-b-20"
                                                   name="credit"
                                                   id="creditd">
                                        </div>
                                        <div class="parsley-input col-md-3 mg-t-20 mg-md-t-0" id="lnWrapper">
                                            <label>{{trans('category.Status')}} :</label>
                                            <select class="form-control form-control-md mg-b-20" id="statusd"
                                                    name="status">
                                                <option value="">{{ trans('orders.all') }}</option>
                                                <option value="active">{{ trans('category.Active') }}</option>
                                                <option value="inactive">{{ trans('category.iActive') }}</option>
                                                <option value="pending_activation">{{ trans('app_users.pActive') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </form>
                                <button type="submit" class="btn btn-primary"
                                        id="s">{{ trans('orders.Sarech') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="row" hidden="hidden" id="hiddenTable">
                <div class="col-xl-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="table-responsive hoverable-table">
                                @can('customer-view')
                                    <table class="table table-hover" id="get_appUser" style=" text-align: center; width:100%;">
                                        <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ trans('app_users.name') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.email') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.mobile') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.charged_balance') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.status') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.activation_code') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{ trans('app_users.name') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.email') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.mobile') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.charged_balance') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.status') }}</th>
                                            <th class="border-bottom-0">{{ trans('app_users.activation_code') }}</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!-- Internal Select2 js-->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <!-- Internal Input tags js-->
    <script src="{{URL::asset('assets/plugins/inputtags/inputtags.js')}}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{URL::asset('assets/plugins/clipboard/clipboard.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/clipboard/clipboard.js')}}"></script>
    <!-- Internal Prism js-->
    <script src="{{URL::asset('assets/plugins/prism/prism.js')}}"></script>

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
        $('#s').click(function (e) {
            e.preventDefault();
            var name = $('#named').val();
            var phoned = $('#phoned').val();
            var creditd = $('#creditd').val();
            var status = $('#statusd').val();
            var lang = local;
            $('#get_appUser').DataTable({
                ordering: false,
                bDestroy: true,
                processing: true,
                serverSide: true,
                searching: false,
                pageLength: 20,
                fixedColumns: true,
                ajax: {
                    url: '{{ url("admin/appUser/get") }}' + '/?name=' + name
                        + '&phone=' + phoned + '&credit=' + creditd + '&status=' + status
                        + '&lang=' + lang,
                    cache: true
                },
                columns: [
                    {
                        'data': 'id',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': null,
                        'className': 'text-center text-lg text-medium',
                        render: function (data, row, type) {
                            return data.first_name ?? "" + " " + data.last_name ?? "";
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return data.email ?? "";
                        },
                        'className': 'text-center text-lg text-medium',
                    },
                    {
                        'data': 'mobile_number',
                    },
                    {
                        'data': 'credit',
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            var phone;
                            if (data.status == 'active') {
                                return `<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;

                            } else if (data.status == 'inactive') {
                                return `<button class="btn btn-danger-gradient btn-block" id="inactive" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                            } else {
                                return `<button class="btn btn-warning-gradient btn-block" id="pending_activation" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('app_users.pActive') }}</button>`;
                            }
                        },
                    },
                    {
                        'data': null,
                        render: function (data, row, type) {
                            return data.activation_code ?? "";
                        },
                    },
                ],
            });

        });
        var table = $('#get_appUser').DataTable({
            ordering: false,
            bDestroy: true,
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: 20,
            fixedColumns: true,
            ajax: '{{ route("get_appUser") }}',
            columns: [

                {
                    'data': 'id',
                    'className': 'text-center text-lg text-medium'
                },
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function (data, row, type) {
                        return data.first_name ?? "" + " " + data.last_name ?? "";
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        return data.email ?? "";
                    },
                    'className': 'text-center text-lg text-medium',
                },
                {
                    'data': 'mobile_number',
                },
                {
                    'data': 'credit',
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        var phone;
                        if (data.status == 'active') {
                            return `<button class="btn btn-success-gradient btn-block" id="active" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.Active') }}</button>`;

                        } else if (data.status == 'inactive') {
                            return `<button class="btn btn-danger-gradient btn-block" id="inactive" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('category.iActive') }}</button>`;
                        } else {
                            return `<button class="btn btn-warning-gradient btn-block" id="pending_activation" data-id="${data.id}" data-viewing_status="${data.status}">{{ trans('app_users.pActive') }}</button>`;
                        }
                    },
                },
                {
                    'data': null,
                    render: function (data, row, type) {
                        return data.activation_code ?? "";
                    },
                },
            ],
        });

        $('input[type=radio][name=type]').change(function () {
            if (this.value == 'customer') {
                $('#hidden').removeAttr('hidden');
                $('#hiddenTable').removeAttr('hidden');
                $('#hiddenFilter').removeAttr('hidden');
            } else if (this.value == 'all') {
                $('#hidden').attr('hidden', 'hidden');
                $('#hiddenTable').attr('hidden', 'hidden');
                $('#hiddenFilter').attr('hidden', 'hidden');

            }
        });
        $(document).ready(function () {
            $('select').selectize({
                sortField: 'text'
            });
        });
    </script>
@endsection
