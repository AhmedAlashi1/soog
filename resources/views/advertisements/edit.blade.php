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
            {{ trans('clothes.edit')}}</span>
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
                    <form  action="{{ route('advertisements.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
{{--                            <input type="hidden" value="{{$type}}" name="type">--}}
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.mobile_user') }}</label>
                                <input type="number" class="form-control" id="mobile_user" name="mobile_user" value="{{$advertisements->user->mobile_number}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.ad_name') }}</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{$advertisements->title_ar}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.price') }}</label>
                                <input type="number" class="form-control" id="url" name="price" value="{{$advertisements->price}}" />
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.Images') }}</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('clothes.ad_description') }}</label>
                                <textarea class="form-control" id="body" name="note" rows="4" required> {{$advertisements->note_ar}}</textarea>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    id="EditClient">{{ trans('notification.sendt') }}</button>
                        </div>
                    </form >
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
        $(document).ready(function () {
            $('.country').change('change', function () {
                var country = jQuery('.country').val();
                console.log(country);
                $.ajax({
                    url: "{{ route('cityFilter') }}",
                    type: "GET",
                    data: {'country': country},
                }).done(function (data) {
                    jQuery('.city').html(data.value);

                }).fail(function (data) {
                    console.log(data)

                });

            });
        });

        $(document).ready(function () {
            $('.category').change('change', function () {
                var category = jQuery('.category').val();
                $.ajax({
                    url: "{{ route('catFilter') }}",
                    type: "GET",
                    data: {'category': category},
                }).done(function (data) {
                    console.log(data.data)
                    jQuery('.sub-category').html(data.value);


                    if(data.data.number_rooms == 1 ){
                        jQuery('#number_rooms').css('display','block');
                    }else {
                        jQuery('#number_rooms').css('display','none');
                    }

                    if(data.data.swimming_pool == 1 ){
                        jQuery('#swimming_pool').css('display','block');
                    }else {
                        jQuery('#swimming_pool').css('display','none');
                    }

                    if(data.data.Jim == 1 ){
                        jQuery('#Jim').css('display','block');
                    }else {
                        jQuery('#Jim').css('display','none');
                    }

                    if(data.data.year == 1 ){
                        jQuery('#year').css('display','block');
                    }else {
                        jQuery('#year').css('display','none');
                    }
                    if(data.data.cere == 1 ){
                        jQuery('#cere').css('display','block');
                    }else {
                        jQuery('#cere').css('display','none');
                    }
                    if(data.data.number_cylinders == 1 ){
                        jQuery('#number_cylinders').css('display','block');
                    }else {
                        jQuery('#number_cylinders').css('display','none');
                    }
                    if(data.data.working_condition == 1 ){
                        jQuery('#working_condition').css('display','block');
                    }else {
                        jQuery('#working_condition').css('display','none');
                    }

                    if(data.data.salary == 1 ){
                        jQuery('#salary').css('display','block');
                    }else {
                        jQuery('#salary').css('display','none');
                    }
                    if(data.data.biography == 1 ){
                        jQuery('#biography').css('display','block');
                    }else {
                        jQuery('#biography').css('display','none');
                    }


                    if(data.data.brand == 1 ){
                        jQuery('#brand_id').css('display','block');
                    }else {
                        jQuery('#brand_id').css('display','none');
                    }


                    if(data.data.educational_level == 1 ){
                        jQuery('#educational_level_id').css('display','block');
                    }else {
                        jQuery('#educational_level_id').css('display','none');
                    }

                    if(data.data.specialization == 1 ){
                        jQuery('#specialization_id').css('display','block');
                    }else {
                        jQuery('#specialization_id').css('display','none');
                    }
                    if(data.data.subjects == 1 ){
                        jQuery('#subjects_id').css('display','block');
                    }else {
                        jQuery('#subjects_id').css('display','none');
                    }
                    if(data.data.animal_type == 1 ){
                        jQuery('#animal_type_id').css('display','block');
                    }else {
                        jQuery('#animal_type_id').css('display','none');
                    }
                    if(data.data.fashion_type == 1 ){
                        jQuery('#fashion_type_id').css('display','block');
                    }else {
                        jQuery('#fashion_type_id').css('display','none');
                    }

                }).fail(function (data) {
                    console.log(data)

                });

            });
        });

        $(document).ready(function () {
            $('.sub-category').change('change', function () {
                var sub_category = jQuery('.sub-category').val();
                console.log(sub_category)
                $.ajax({
                    url: "{{ route('inputFilter') }}",
                    type: "GET",
                    data: {'sub_category': sub_category},
                }).done(function (data) {
                    console.log(data.data)
                    jQuery('.inputs-filter').html(data.value);

                    if(data.data.number_rooms == 1 ){
                        jQuery('#number_rooms').css('display','block');
                    }else {
                        jQuery('#number_rooms').css('display','none');
                    }

                    if(data.data.swimming_pool == 1 ){
                        jQuery('#swimming_pool').css('display','block');
                    }else {
                        jQuery('#swimming_pool').css('display','none');
                    }

                    if(data.data.Jim == 1 ){
                        jQuery('#Jim').css('display','block');
                    }else {
                        jQuery('#Jim').css('display','none');
                    }

                    if(data.data.year == 1 ){
                        jQuery('#year').css('display','block');
                    }else {
                        jQuery('#year').css('display','none');
                    }
                    if(data.data.cere == 1 ){
                        jQuery('#cere').css('display','block');
                    }else {
                        jQuery('#cere').css('display','none');
                    }
                    if(data.data.number_cylinders == 1 ){
                        jQuery('#number_cylinders').css('display','block');
                    }else {
                        jQuery('#number_cylinders').css('display','none');
                    }
                    if(data.data.working_condition == 1 ){
                        jQuery('#working_condition').css('display','block');
                    }else {
                        jQuery('#working_condition').css('display','none');
                    }

                    if(data.data.salary == 1 ){
                        jQuery('#salary').css('display','block');
                    }else {
                        jQuery('#salary').css('display','none');
                    }

                    if(data.data.biography == 1 ){
                        jQuery('#biography').css('display','block');
                    }else {
                        jQuery('#biography').css('display','none');
                    }
                    if(data.data.brand == 1 ){
                        jQuery('#brand_id').css('display','block');
                    }else {
                        jQuery('#brand_id').css('display','none');
                    }


                    if(data.data.educational_level == 1 ){
                        jQuery('#educational_level_id').css('display','block');
                    }else {
                        jQuery('#educational_level_id').css('display','none');
                    }

                    if(data.data.specialization == 1 ){
                        jQuery('#specialization_id').css('display','block');
                    }else {
                        jQuery('#specialization_id').css('display','none');
                    }
                    if(data.data.subjects == 1 ){
                        jQuery('#subjects_id').css('display','block');
                    }else {
                        jQuery('#subjects_id').css('display','none');
                    }
                    if(data.data.animal_type == 1 ){
                        jQuery('#animal_type_id').css('display','block');
                    }else {
                        jQuery('#animal_type_id').css('display','none');
                    }
                    if(data.data.fashion_type == 1 ){
                        jQuery('#fashion_type_id').css('display','block');
                    }else {
                        jQuery('#fashion_type_id').css('display','none');
                    }






                }).fail(function (data) {
                    console.log(data)

                });

            });
        });

        $(document).ready(function () {
            $("select[name=country_id] option[value=@json($advertisements->country_id)]").attr("selected", "selected");

            $("select[name=governorates_id] option[value=@json($advertisements->governorates_id)]").attr("selected", "selected");
            {{--if (@json() == '1') {--}}
            {{-- --}}
            {{--} else {--}}
            {{--    $("select option[value='0']").attr("selected", "selected");--}}
            {{--}--}}

        });

    </script>
@endsection
