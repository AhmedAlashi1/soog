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
            {{ trans('category.content_title') }}</span>
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
                        <h6 class="modal-title">{{ trans('category.content_title') }}</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                                                  type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <ul id="list_error_message"></ul>
                        <form id="formcategory" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_E') }} :</label>
                                    <input type="text" class="form-control" name="title_en" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_A') }} :</label>
                                    <input type="text" class="form-control" name="title_ar" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Image') }} :</label>
                                    <input type="file" class="form-control" name="image" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.color') }} :</label>
                                    <input type="color"  class="form-control" name="color" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="form-label">{{ trans('category.Status') }} :</label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{ trans('category.Active') }}</option>
                                        <option value="0">{{ trans('category.iActive') }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                        <label class="ckbox">
                                            <input type="checkbox" class="mb-2" name="number_rooms"  value="1">
                                            <span> {{ trans('category.number_rooms') }}</span>
                                        </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="swimming_pool" value="1">
                                        <span>{{ trans('category.swimming_pool') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="Jim"  value="1">
                                        <span> {{ trans('category.Jim') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="working_condition" value="1">
                                        <span>{{ trans('category.working_condition') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="year" value="1">
                                        <span>{{ trans('category.year') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="cere"  value="1">
                                        <span> {{ trans('category.cere') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="number_cylinders" value="1">
                                        <span>{{ trans('category.number_cylinders') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="brand"  value="1">
                                        <span> {{ trans('category.brand') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="salary"  value="1">
                                        <span> {{ trans('category.salary') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="educational_level"  value="1">
                                        <span> {{ trans('category.educational_level') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="specialization"  value="1">
                                        <span> {{ trans('category.specialization') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="biography"  value="1">
                                        <span> {{ trans('category.biography') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="animal_type"  value="1">
                                        <span> {{ trans('category.animal_type') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="fashion_type"  value="1">
                                        <span> {{ trans('category.fashion_type') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="subjects"  value="1">
                                        <span> {{ trans('category.subjects') }} aa</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="location"     value="1">
                                        <span> {{ trans('category.location') }}</span>
                                    </label>
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
                                    <label for="exampleInputEmail1">{{ trans('category.Title_E') }} :</label>
                                    <input type="text" class="form-control" id="title_en" name="title_en" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Title_A') }} :</label>
                                    <input type="text" class="form-control" id="title_ar" name="title_ar" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.Image') }} :</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">{{ trans('category.color') }} :</label>
                                    <input type="color"  class="form-control" name="color"  id="color" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="form-label">{{ trans('category.Status') }} :</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">{{ trans('category.Active') }}</option>
                                        <option value="0">{{ trans('category.iActive') }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="number_rooms" id="number_rooms"  value="1">
                                        <span> {{ trans('category.number_rooms') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="swimming_pool"  id="swimming_pool" value="1">
                                        <span>{{ trans('category.swimming_pool') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="Jim"  id="Jim" value="1">
                                        <span> {{ trans('category.Jim') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="working_condition" id="working_condition"  value="1">
                                        <span>{{ trans('category.working_condition') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="year"  id="year" value="1">
                                        <span>{{ trans('category.year') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="cere" id="cere"   value="1">
                                        <span> {{ trans('category.cere') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="number_cylinders" id="number_cylinders" value="1">
                                        <span>{{ trans('category.number_cylinders') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="brand"  id="brand" value="1">
                                        <span> {{ trans('category.brand') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="salary" id="salary"  value="1">
                                        <span> {{ trans('category.salary') }}</span>
                                    </label>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="educational_level" id="educational_level"   value="1">
                                        <span> {{ trans('category.educational_level') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="specialization" id="specialization"  value="1">
                                        <span> {{ trans('category.specialization') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="biography"  id="biography" value="1">
                                        <span> {{ trans('category.biography') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="animal_type"  id="animal_type" value="1">
                                        <span> {{ trans('category.animal_type') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="fashion_type" id="fashion_type"   value="1">
                                        <span> {{ trans('category.fashion_type') }}</span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="subjects"  id="subjects"  value="1">
                                        <span> {{ trans('category.subjects') }} </span>
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="ckbox">
                                        <input type="checkbox" class="mb-2" name="location"  id="location"  value="1">
                                        <span> {{ trans('category.location') }}</span>
                                    </label>
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
                        @can('categories-create')
                            <div class="row row-xs wd-xl-80p">
                                <div class="col-sm-6 col-md-3 mg-t-10">
                                    <button class="btn btn-info-gradient btn-block" id="ShowModalAddCategory">
                                        <a href="#" style="font-weight: bold; color: beige;">{{ trans('category.Add_CategoryS') }}</a>
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
                                        <th class="border-bottom-0">{{ trans('category.Title') }}</th>
                                        {{--                                <th class="border-bottom-0">{{ trans('category.Description') }}</th>--}}
                                        <th class="border-bottom-0">{{ trans('category.Image') }}</th>
                                        {{--                                <th class="border-bottom-0">{{ trans('clothes.international') }}</th>--}}
                                        <th class="border-bottom-0">{{ trans('category.Status') }}</th>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

{{--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>--}}
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>--}}
{{--    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

{{--    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>--}}
    <script>
        var local = "{{ App::getLocale() }}";
        var id = "{{ request()->route('id') }}";
        var table = $('#get_categories').DataTable({
            // processing: true,
            ajax: '{{ url("admin/category/show") }}/' + id,
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
                {
                    'data': null,
                    'className': 'text-center text-lg text-medium',
                    render: function(data, row, type) {
                        if (local == "en") {
                            return data.title_en;
                        } else {
                            return data.title_ar;
                        }
                    },
                },

                // {
                //     'data': null,
                //     'className': 'text-center text-lg text-medium',
                //     render: function(data, row, type) {
                //         if(data.description_en){
                //             if (local == "en") {
                //                 return data.description_en;
                //             } else {
                //                 return data.description_ar;
                //             }
                //         }else{
                //             return "لايوجد وصف";
                //         }
                //
                //     },
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
                        var phone;
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
{{--                @can('product-view')--}}
                        {{--                <a href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/admin/clothes') }}/${data.id}" class="btn btn-success btn-sm" title="الاصناف"><i class="fa fa-clipboard"></i> {{ trans('category.Prodects') }}</a>--}}
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
        function sendOrderToServer() {
            var order = [];
            var token = $('meta[name="csrf-token"]').attr('content');
            $('tr.row1').each(function (index, element) {
                order.push({
                    id: $(this).attr('data-id'),
                    position: index + 1
                });
            });
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('categories_sortable') }}",
                data: {
                    order: order,
                    _token: token
                },
                success: function (response) {
                    if (response.status == "success") {
                        console.log(response);
                    } else {
                        console.log(response);
                    }
                }
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
        //  view modal Category
        $(document).on('click', '#ShowModalAddCategory', function(e) {
            e.preventDefault();
            $('#modalAddCategory').modal('show');
        });
        // Category admin
        $(document).on('click', '.AddCategory', function(e) {
            e.preventDefault();
            //   var id_category = $('#id_category').val();

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
                url: '{{ route("add_category") }}' + '?type=2&parent_id=' + id,
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
            $('#modalEditCategory').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url("admin/category/edit") }}/' + id_category,
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
                        $('#description_en').val(response.data.description_en);
                        $('#description_ar').val(response.data.description_ar);
                        $('#color').val(response.data.color);
                        if (response.data.number_rooms == '1') {
                            $('#number_rooms').attr('checked', true);
                        }
                        if (response.data.swimming_pool == '1') {
                            $('#swimming_pool').attr('checked', true);
                        }
                        if (response.data.Jim == '1') {
                            $('#Jim').attr('checked', true);
                        }
                        if (response.data.working_condition == '1') {
                            $('#working_condition').attr('checked', true);
                        }
                        if (response.data.year == '1') {
                            $('#year').attr('checked', true);
                        }
                        if (response.data.cere == '1') {
                            $('#cere').attr('checked', true);
                        }
                        if (response.data.number_cylinders == '1') {
                            $('#number_cylinders').attr('checked', true);
                        }
                        if (response.data.brand == '1') {
                            $('#brand').attr('checked', true);
                        }
                        if (response.data.salary == '1') {
                            $('#salary').attr('checked', true);
                        }
                        if (response.data.educational_level == '1') {
                            $('#educational_level').attr('checked', true);
                        }
                        if (response.data.specialization == '1') {
                            $('#specialization').attr('checked', true);
                        }
                        if (response.data.biography == '1') {
                            $('#biography').attr('checked', true);
                        }
                        if (response.data.animal_type == '1') {
                            $('#animal_type').attr('checked', true);
                        }
                        if (response.data.fashion_type == '1') {
                            $('#fashion_type').attr('checked', true);
                        }
                        if (response.data.subjects == '1') {
                            $('#subjects').attr('checked', true);
                        }
                        if (response.data.location == '1') {
                            $('#location').attr('checked', true);
                        }


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

            // console.log(number_room);
            var data = {
                title_en: $('#title_en').val(),
                title_ar: $('#title_ar').val(),
                description_en: $('#description_en').val(),
                description_ar: $('#description_ar').val(),
                image: $('#image').val(),
                status: $('#status').val(),

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
                url: '{{ url("admin/category/update") }}/' + id_category,
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
                    url: '{{ url("admin/category/delete") }}/' + id,
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
