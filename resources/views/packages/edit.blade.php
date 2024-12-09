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
    <style>
        .show {display: none;}
    </style>

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
                    <form id="formeditadmin" action="{{url('admin/packages/update')}}" method="post">
                        @method('patch')
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{$type}}" name="type">
                            <input type="hidden" value="{{$id}}" name="id">

                            <div class="form-group col-md-12" >
                                <label class="form-label">{{ trans('packages.type') }} :</label>
                                <select name="type" class="form-control" id="types" >
                                    <option value="1">باقات تميز منتج</option>
                                    <option value="2">باقات زيادة مشاهدات</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12"   id="place_installation">
                                <label class="form-label">{{ trans('packages.place_installation') }} :</label>
                                <select name="place_installation" class="form-control" id="place_installation">
                                    <option value="1">داخل الرائيسية</option>
                                    <option value="2"> داخل صفحة الاقسام </option>
                                    <option value="3"> داخل صفحة الاقسام والرائيسية </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_title') }}</label>
                                <input type="text" class="form-control" id="title" name="title_ar" required value="{{$packages->title_ar}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('packages.package_title_en') }}</label>
                                <input type="text" class="form-control" id="url" name="title_en" value="{{$packages->title_en}}">
                            </div>


                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">{{ trans('packages.price') }}</label>
                                <input type="number" class="form-control" id="price" name="price" value="{{$packages->price}}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputEmail1">{{ trans('packages.Term') }}</label>

                                <input type="number" class="form-control" id="days" name="days" value="{{$packages->days}}">
                            </div>


                            <div class="form-group col-md-3" id="repeat_duration">
                                <label for="exampleInputEmail1">مدة التكرر</label>

                                <input type="number" class="form-control"  name="repeat_duration" value="{{$packages->repeat_duration}}">
                            </div>
                            <div class="form-group col-md-3" id="number_repetitions">
                                <label for="exampleInputEmail1">عدد مرات التكرر</label>

                                <input type="number" class="form-control"  name="number_repetitions" value="{{$packages->number_repetitions}}">
                            </div>
                        </div>


                            <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    id="EditClient">{{ trans('notification.sendt') }}</button>
                        </div>
                    </form>
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
       {{--$('#types').val(@json($packages->type));--}}
       var  type = @json($packages->type);
       if (type == 1) {
           $("select[name='type'] option[value='1']").attr("selected", "selected");
       } else {
           $("select[name='type'] option[value='2']").attr("selected", "selected");
       }
       $('#place_installation').val(@json($packages->place_installation));
       var  place_installation = @json($packages->place_installation);
       if (place_installation == 1) {
           $("select[name='place_installation'] option[value='1']").attr("selected", "selected");
       } else if (place_installation == 2) {
           $("select[name='place_installation'] option[value='2']").attr("selected", "selected");
       }else {
           $("select[name='place_installation'] option[value='3']").attr("selected", "selected");

       }

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
       }

   </script>


@endsection
