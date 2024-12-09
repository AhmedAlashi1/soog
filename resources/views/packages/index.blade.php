@extends('layouts.master')
@section('css')

    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <link href="{{URL::asset('assets/plugins/prism/prism.css')}}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{URL::asset('assets/plugins/inputtags/inputtags.css')}}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css')}}" rel="stylesheet">

    <style>
        .show {display: none;}
    </style>
@section('title')
    الاقسام
@stop

@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ trans('orders.home') }}</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                {{ trans('packages.Package_management') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- row -->
    <div class="row">


        <div class="col-xl-12">
            <div class="card mg-b-20">

                @can('packages-create')
                <div class="card-header pb-0">
                    <div class="row row-xs wd-xl-80p">
                        <div class="col-sm-6 col-md-3 mg-t-10">
                            <button class="btn btn-info-gradient btn-block" >

                                <a data-toggle="modal" href="#modaldemo8"  style="font-weight: bold; color: beige;">{{ trans('packages.add') }}</a>
                            </button>

                        </div>
                    </div>
                </div>
                @endcan


                    @can('packages-view')
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        <table class="table table-hover" id="example1" data-page-length='10' style=" text-align: center;">
                            <thead>
                            <tr>
                                <th class="wd-5p border-bottom-0">#</th>
{{--                                <th class="border-bottom-0">صورة الباقات</th>--}}

                                <th class="wd-15p border-bottom-0">{{ trans('packages.package_title') }}</th>
                                <th class="wd-15p border-bottom-0">{{ trans('packages.type') }}</th>
                                <th class="wd-5p border-bottom-0">{{ trans('packages.price') }}</th>
                                <th class="wd-5p border-bottom-0">{{ trans('packages.Term') }}</th>
                                <th class="wd-5p border-bottom-0">{{ trans('packages.status') }}</th>
                                <th class="wd-15p border-bottom-0">{{ trans('packages.created_at') }}</th>

                                @canany(['packages-update','packages-delete'])
                                <th class="wd-15p border-bottom-0">{{trans('orders.Processes')}}</th>
                                @endcanany

                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            @foreach ($packages as $x)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
{{--                                    <td><img src="{{url('').'/'.$x->image}}" style="width: 25px;height: 25px"></td>--}}

                                    <td>{{ $x->title_ar }}</td>
                                    <td>
                                            @if($x->type == 1)
                                                باقة تميز اعلان
                                        @elseif($x->type == 2)
                                            باقة زيادة المشاهدات
                                        @endif
                                    </td>
                                    <td>{{ $x->price }}</td>
                                    <td>{{ $x->days }}</td>
                                    <td>
                                        @if ($x->status == 1)
                                            <a  data-effect="effect-scale"
                                                data-id="{{ $x->id }}"

                                                data-toggle="modal" href="#modaldemo10"><span class="badge badge-success">
                                                    <i class="icon-check"></i></span></a>
                                        @else
                                            <a  data-effect="effect-scale"
                                                data-id="{{ $x->id }}"
                                                data-name="{{ $x->title_ar }}"

                                                data-toggle="modal" href="#modaldemo10"><span class="badge badge-danger">
                                                    <i class=" icon-close"></i></span></a>
                                        @endif
                                    </td>
                                    <td>{{ $x->created_at }}</td>


                                    <td>
                                        @can('packages-update')
                                    <a class=" btn btn-sm btn-info" data-effect="effect-scale"

                                       href="{{ url('admin/packages/').'/'. $x->id.'/edit' }}" title="تعديل"><i class="las la-pen"></i></a>

                                        @endcan
                                            @can('packages-delete')
                                    <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                       data-id="{{ $x->id }}" .
                                       data-name="{{ $x->title_ar }}"


                                       data-toggle="modal" href="#modaldemo9" title="حذف"><i
                                            class="las la-trash"></i></a>
                                            @endcan

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                    @endcan
            </div>
        </div>

        {{--add--}}
        <div class="modal" id="modaldemo8">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">اضافة الباقة</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                                                   type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('packages.store') }}" method="post"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-12" >
                                    <label class="form-label">{{ trans('packages.type') }} :</label>
                                    <select name="type" class="form-control" id="types" >
                                        <option value="1">باقات تميز منتج</option>
                                        <option value="2">باقات زيادة مشاهدات</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12"   id="place_installation">
                                    <label class="form-label">{{ trans('packages.place_installation') }} :</label>
                                    <select name="place_installation" class="form-control">
                                        <option value="1">داخل الرائيسية</option>
                                        <option value="2"> داخل صفحة الاقسام </option>
                                        <option value="3"> داخل صفحة الاقسام والرائيسية </option>
                                    </select>
                                </div>


                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">عنوان الباقة</label>
                                    <input type="text" class="form-control" id="name" name="title_ar">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1"> عنوان الباقة انجليزي</label>

                                    <input type="text" class="form-control" id="name_en" name="title_en">
                                </div>


                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">السعر</label>
                                    <input type="number" class="form-control" id="price" name="price">
                                </div>

                                <div class="form-group col-md-12" id="days">
                                    <label for="exampleInputEmail1">المدى</label>

                                    <input type="number" class="form-control"   name="days">
                                </div>


                                <div class="form-group col-md-12" id="repeat_duration">
                                    <label for="exampleInputEmail1">مدة التكرر</label>

                                    <input type="number" class="form-control"  name="repeat_duration">
                                </div>
                                <div class="form-group col-md-12" id="number_repetitions">
                                    <label for="exampleInputEmail1">عدد مرات التكرر</label>

                                    <input type="number" class="form-control"  name="number_repetitions">
                                </div>




                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">{{trans('packages.save')}}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('packages.close')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Basic modal -->


        </div>

        <!-- update stuts -->

        <div class="modal" id="modaldemo10">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title"> {{ trans('packages.Package_status_change') }}</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                                                    type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{route('packages.update.status')}}" method="post">


                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>
                                         <p>{{trans('packages.q')}}</p>
{{--                                    <p>هل انت متاكد من تغير حالة الباقة ؟ </p>--}}

                                </strong>

                            </div>


                            <input type="hidden" name="id" id="id" value="">
                            {{--                            <input class="form-control" name="name" id="name" type="text" readonly>--}}
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">{{trans('packages.save')}}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('packages.close')}}</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
        <!-- edit -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تعديل  الباقة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form action="packages/update" method="post" autocomplete="off" enctype="multipart/form-data">
                            {{ method_field('patch') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id" value="">
                            <div class="row">
                                <div class="form-group col-md-12" >
                                    <label class="form-label">{{ trans('packages.type') }} :</label>
                                    <select name="type" class="form-control" id="types" >
                                        <option value="1">باقات تميز منتج</option>
                                        <option value="2">باقات زيادة مشاهدات</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12"   id="place_installation">
                                    <label class="form-label">{{ trans('packages.place_installation') }} :</label>
                                    <select name="place_installation    " class="form-control">
                                        <option value="1">داخل الرائيسية</option>
                                        <option value="2"> داخل صفحة الاقسام </option>
                                        <option value="3"> داخل صفحة الاقسام والرائيسية </option>
                                    </select>
                                </div>


                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">عنوان الباقة</label>
                                    <input type="text" class="form-control" id="name" name="title_ar">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1"> عنوان الباقة انجليزي</label>

                                    <input type="text" class="form-control" id="name_en" name="title_en">
                                </div>


                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">السعر</label>
                                    <input type="number" class="form-control" id="price" name="price">
                                </div>

                                <div class="form-group col-md-12" id="days">
                                    <label for="exampleInputEmail1">المدى</label>

                                    <input type="number" class="form-control"   name="days">
                                </div>


                                <div class="form-group col-md-12" id="repeat_duration">
                                    <label for="exampleInputEmail1">مدة التكرر</label>

                                    <input type="number" class="form-control"  name="repeat_duration">
                                </div>
                                <div class="form-group col-md-12" id="number_repetitions">
                                    <label for="exampleInputEmail1">عدد مرات التكرر</label>

                                    <input type="number" class="form-control"  name="number_repetitions">
                                </div>




                            </div>





                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">{{trans('orders.Submit')}}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('orders.Close')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- delete -->
        <div class="modal" id="modaldemo9">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{trans('packages.delete_profession')}}</h6><button aria-label="Close" class="close" data-dismiss="modal"
                                                                                                      type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="packages/destroy" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>
{{--                                    <p>{{trans('orders.delete_message1')}}</p>--}}
                                    <p>{{trans('packages.delete_message2')}}</p>

                                </strong>

                            </div>


                            <input type="hidden" name="id" id="id" value="">
                            <input class="form-control" name="name" id="name" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">{{trans('packages.save')}}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('packages.close')}}</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script>

    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <script>
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var title_ar = button.data('title_ar')
            var title_en = button.data('title_en')
            var price = button.data('price')
            var days = button.data('days')




            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #title_ar').val(title_ar);
            modal.find('.modal-body #title_en').val(title_en);
            modal.find('.modal-body #price').val(price);
            modal.find('.modal-body #days').val(days);


            // if (status == 1) {
            //     modal.find('.modal-body #status').html("فعال");
            // }else {
            //     modal.find('.modal-body #status').html("غير فعال");
            // }


        })

    </script>

    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')

            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
        })

    </script>
    <script>
        $('#modaldemo10').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var name = button.data('name')

            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #name').val(name);
        })

    </script>
    <script>

        $('#types').on('click', function(){
            search();
        });
        search();
        function search(){
            var keyword = $('#types').val();
            if (keyword != 1){
                // alert(keyword)
                document.getElementById("place_installation").classList.toggle("show");
                document.getElementById("days").classList.toggle("show");


                // $("#place_installation").css("display:none");
            }else {
                document.getElementById("repeat_duration").classList.toggle("show");
                document.getElementById("number_repetitions").classList.toggle("show");
            }
            // alert(keyword)

            {{--$.post('{{ route("services.search") }}',--}}
            {{--    {--}}
            {{--        _token: $('meta[name="csrf-token"]').attr('content'),--}}
            {{--        keyword:keyword--}}
            {{--    },--}}
            {{--    function(data){--}}

            {{--        table_post_row2(data);--}}
            {{--        console.log(data);--}}
            {{--    });--}}
        }
    </script>

@endsection
