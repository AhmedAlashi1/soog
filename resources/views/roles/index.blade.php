@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('admins.home') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                {{ trans('roles.content_title') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="main-body">
        <div class="row">
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
            @include('roles.model-from')
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row row-xs wd-xl-80p">
                            @can('role-create')
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block ShowModel" id="ShowModalAddCategory">
                                        <a href="#" style="font-weight: bold; color: beige;">{{ trans('roles.page_title_form') }}</a>
                                    </button>
                                </div>
                            @endcan
                        </div>
                        <!-- <h3>roles
                        <button class="btn btn-primary float-right ShowModel">Add
                            roles</button>
                    </h3> -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive hoverable-table">
                            @can('role-view')
                                <table class="table table-hover" id="get_roles">
                                    <div class="alert alert-success message" style="display:none"></div>
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{ trans('roles.name') }}</th>
                                        <th class="border-bottom-0">{{ trans('roles.Count') }}</th>
                                        <th class="border-bottom-0">{{ trans('roles.Date') }}</th>
                                        <th class="border-bottom-0">
                                            @canany([ 'role-update' , 'role-delete' ])
                                                {{ trans('category.Processes') }}
                                            @endcanany
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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
        $(document).ready(function () {
            var table = $('#get_roles').DataTable({
                processing: false,
                ajax: '{!! url("admin/get_roles") !!}',
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
                        'data': 'name',
                        'className': 'text-center text-lg text-medium'
                    },
                    {
                        'data': 'users_count',
                        'className': 'text-center text-lg text-medium'
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
                        render: function (data, row, type) {
                            return `
                            @can('role-update')
                            <a class="modal-effect btn btn-sm btn-info ShowEditeModel" data-id="${data.id}" href="#"><i class="las la-pen"></i></a>
                            @endcan
                            @can('role-delete')
                            <button class="modal-effect btn btn-sm btn-danger DeleteRole" data-id="${data.id}" data-namee="${data.name}"><i class="las la-trash"></i></button>
                            @endcan
                            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('#get_roles').addClass('col-sm-12');

            function r(f = []) {
                for (var i = 0; i < f.length; i++) {
                    $('#' + f[i]).removeClass('on');
                }
            }

            var Permissions = ['role-view', 'role-create', 'role-update',
                'role-delete'
            ];

            var Members = ['member-view', 'member-create', 'member-update',
                'member-delete'
            ];

            var Settings = ['setting-view', 'setting-create', 'setting-update',
                'setting-delete'
            ];

            var categories = ['categories-view', 'categories-create',
                'categories-update', 'categories-delete'
            ];

            var ads = ['ads-view', 'ads-create', 'ads-update', 'ads-delete'];

            var Products = ['product-view', 'product-create', 'product-update', 'product-delete'];

            var Customers = ['customer-view', 'customer-create', 'customer-update',
                'customer-delete'
            ];

            var order = ['order-view', 'order-create', 'order-update',
                'order-delete'
            ];

            var contact = ['contact-view', 'contact-create', 'contact-update',
                'contact-delete'
            ];

            var productList = ['productList-view', 'productList-create', 'productList-update',
                'productList-delete'
            ];

            var paymentMethod = ['paymentMethod-view', 'paymentMethod-create', 'paymentMethod-update',
                'paymentMethod-delete'
            ];

            var deliveryMethods = ['deliveryMethods-view', 'deliveryMethods-create', 'deliveryMethods-update',
                'deliveryMethods-delete'
            ];

            var Reports = ['report-view', 'report-create', 'report-update',
                'report-delete'
            ];

            var discountCodes = ['discountCodes-view', 'discountCodes-create', 'discountCodes-update',
                'discountCodes-delete'
            ];

            var workTime = ['workTime-view', 'workTime-create', 'workTime-update',
                'workTime-delete'
            ];

            var deliveryHour = ['deliveryHour-view', 'deliveryHour-create', 'deliveryHour-update',
                'deliveryHour-delete'
            ];

            var region = ['region-view', 'region-create', 'region-update',
                'region-delete'
            ];
            var Notifications = ['notification-view', 'notification-create', 'notification-update',
                'notification-delete'
            ];
            var Archives = ['archives-view', 'archives-create', 'archives-update',
                'archives-delete'
            ];
            var roles = [
                'role-all',
                'role-view', 'role-create', 'role-update', 'role-delete',
                'member-all',
                'member-view', 'member-create', 'member-update', 'member-delete',
                'setting-all',
                'setting-view', 'setting-create', 'setting-update', 'setting-delete',
                'categories-all',
                'categories-view', 'categories-create', 'categories-update', 'categories-delete',
                'ads-all',
                'ads-view', 'ads-create', 'ads-update', 'ads-delete',
                'product-all',
                'product-view', 'product-create', 'product-update', 'product-delete',
                'customer-all',
                'customer-view', 'customer-create', 'customer-update', 'customer-delete',
                'order-all',
                'order-view', 'order-create', 'order-update', 'order-delete',
                'contact-all',
                'contact-view', 'contact-create', 'contact-update', 'contact-delete',
                'productList-all',
                'productList-view', 'productList-create', 'productList-update', 'productList-delete',
                'paymentMethod-all',
                'paymentMethod-view', 'paymentMethod-create', 'paymentMethod-update', 'paymentMethod-delete',
                'deliveryMethods-all',
                'deliveryMethods-view', 'deliveryMethods-create', 'deliveryMethods-update', 'deliveryMethods-delete',
                'report-all',
                'report-view', 'report-create', 'report-update', 'report-delete',
                'discountCodes-all',
                'discountCodes-view', 'discountCodes-create', 'discountCodes-update', 'discountCodes-delete',
                'workTime-all',
                'workTime-view', 'workTime-create', 'workTime-update', 'workTime-delete',
                'deliveryHour-all',
                'deliveryHour-view', 'deliveryHour-create', 'deliveryHour-update', 'deliveryHour-delete',
                'region-all',
                'region-view', 'region-create', 'region-update', 'region-delete',
                'notification-all',
                'notification-view', 'notification-create', 'notification-update', 'notification-delete',
                'archives-all',
                'archives-view', 'archives-create', 'archives-update', 'archives-delete'
            ];

            function all(all, arr = []) {
                $('#' + all + 'a').click(function (e) {
                    e.preventDefault();
                    for (var i = 0; i < arr.length; i++) {
                        if ($('#' + all + "a").hasClass('on')) {
                            $('#' + arr[i] + 'a').addClass('on');
                        } else {
                            $('#' + arr[i] + 'a').removeClass('on');
                        }

                    }
                });
                $('#' + all).click(function (e) {
                    e.preventDefault();
                    for (var i = 0; i < arr.length; i++) {
                        if ($('#' + all).hasClass('on')) {
                            $('#' + arr[i]).addClass('on');
                        } else {
                            $('#' + arr[i]).removeClass('on');
                        }
                    }
                });
            }

            all('member-all', Members);
            all('role-all', Permissions);
            all('setting-all', Settings);
            all('categories-all', categories);
            all('ads-all', ads);
            all('customer-all', Customers);
            all('order-all', order);
            all('region-all', region);
            all('product-all', Products);
            all('contact-all', contact);
            all('productList-all', productList);
            all('paymentMethod-all', paymentMethod);
            all('deliveryMethods-all', deliveryMethods);
            all('report-all', Reports);
            all('discountCodes-all', discountCodes);
            all('workTime-all', workTime);
            all('deliveryHour-all', deliveryHour);
            all('notification-all', Notifications);
            all('archives-all', Archives);
            $('#allh').click(function (e) {
                e.preventDefault();
                for (var i = 0; i < roles.length; i++) {
                    if ($('#allh').hasClass('on')) {
                        $('#' + roles[i] + 'a').addClass('on');
                    } else {
                        $('#' + roles[i] + 'a').removeClass('on');
                    }
                }
            });
            $('#allu').click(function (e) {
                e.preventDefault();
                for (var i = 0; i < roles.length; i++) {
                    if ($('#allu').hasClass('on')) {
                        $('#' + roles[i]).addClass('on');
                    } else {
                        $('#' + roles[i]).removeClass('on');
                    }
                }
            });
            $(document).on('click', '.ShowModel', function (e) {
                e.preventDefault();

                $('#CreateRolesModal').modal('show');
                $('.CreateRolesBut').click(function (e) {
                    e.preventDefault();
                    const points = new Array();
                    for (var i = 0; i < roles.length; i++) {
                        if ($('#' + roles[i] + "a").hasClass('on')) {
                            points.push($('#' + roles[i] + "a").data('v'));
                        }
                    }
                    console.log(points);
                    var data = {
                        name: $('#user_name').val(),
                        permissions: points,
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("admin/roles") }}',
                        data: data,
                        success: function (response) {
                            $('#CreateRolesModal').modal('hide');
                            table.ajax.reload();
                        }
                    });
                });
            });
            $(document).on('click', '.ShowEditeModel', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#EditeRolesModal').modal('show');
                $.ajax({
                    type: 'GET',
                    url: '{{ url("admin/show") }}/' + id + "/" + 0,
                    data: "",
                    success: function (response) {
                        // console.log(response.data.id);
                        if (response.status == 404) {
                            console.log('error');
                            $('#error_message').html("");
                            $('#error_message').addClass("alert alert-danger");
                            $('#error_message').text(response.message);
                        } else {
                            function r(f = []) {
                                for (var i = 0; i < f.length; i++) {
                                    $('#' + f[i]).removeClass('on');
                                }
                            }

                            r(roles);
                            $('#edit_user_name').val(response.data.name);
                            for (var i = 0; i < response.data.permissions.length; i++) {
                                if (response.data.permissions[i] == $('#' + response.data
                                    .permissions[i]).data('v')) {
                                    $('#' + response.data.permissions[i]).addClass('on');
                                } else {
                                    $('#' + response.data.permissions[i]).removeClass('on');
                                }
                            }
                            gg(response.data.id, roles);
                        }
                    }
                });
            });

            function gg(k, arrayy, points) {
                $(document).off("click", ".ERolesButt").on("click", ".ERolesButt", function (e) {
                    e.preventDefault();
                    const point = new Array();
                    for (var i = 0; i < arrayy.length; i++) {
                        if ($('#' + arrayy[i]).hasClass('on')) {
                            point.push($('#' + arrayy[i]).data('v'));
                        }
                    }
                    console.log(point);
                    var data = {
                        id: k,
                        permissions: point,
                        name: $('#edit_user_name').val(),
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                .attr('content')
                        }
                    });
                    $.ajax({
                        type: 'post',
                        url: '{{ url("admin/update/r") }}/' + k,
                        data: data,
                        success: function (response) {
                            $('#EditeRolesModal').modal('hide');
                            table.ajax.reload();
                        }
                    });
                });
            }

            $(document).on('click', '.DeleteRole', function (e) {
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
                        url: '{{ url("admin/destroy") }}/' + id + '/' + 0,
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
            {{--$(document).on('click', '.DeleteRole', function(e) {--}}
            {{--    e.preventDefault();--}}
            {{--    var id = $(this).data('id');--}}
            {{--    var admin_id = $(this).data('user');--}}
            {{--    $.ajaxSetup({--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--        }--}}
            {{--    });--}}
            {{--    $.ajax({--}}
            {{--        type: 'DELETE',--}}
            {{--        url: '{{ url("admin/destroy") }}/' + id + "/" + admin_id,--}}
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
        });
    </script>
@endsection
