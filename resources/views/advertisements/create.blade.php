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
            {{ trans('clothes.add')}}</span>
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
                                <input type="number" class="form-control" id="mobile_user" name="mobile_user" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.ad_name') }}</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.price') }}</label>
                                <input type="number" class="form-control" id="url" name="price">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{ trans('clothes.Images') }}</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">{{ trans('clothes.ad_description') }}</label>
                                <textarea class="form-control" id="body" name="note" rows="4" required></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label"> {{ trans('clothes.country') }} :</label>
                                <select name="country_id"  class="form-control country">
                                    <option value="0"></option>

                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->title_ar}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label"> {{ trans('clothes.city') }} :</label>
                                <select name="governorates_id"  class="form-control city"   >

                                </select>
                            </div>




                            <div class="form-group col-md-6">
                                <label class="form-label"> {{ trans('clothes.main_section') }} :</label>
                                <select name="MainSection"  class="form-control category">
                                    <option value="0"></option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->title_ar}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label"> {{ trans('clothes.subsection') }} :</label>
                                <select name="cat_id"  class="form-control sub-category"   >
                                    <option value="0"></option>

                                </select>
                            </div>


                            <div class="form-group col-md-2">
                                <label class="ckbox">
                                    <input type="checkbox" class="mb-2" name="chat" id="chat"   value="1">
                                    <span>chat</span>
                                </label>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="ckbox">
                                    <input type="checkbox" class="mb-2" name="whatsApp" id="whatsApp"  value="1">
                                    <span>whatsApp</span>
                                </label>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="ckbox">
                                    <input type="checkbox" class="mb-2" name="email" id="email"   value="1">
                                    <span>email</span>
                                </label>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="ckbox">
                                    <input type="checkbox" class="mb-2" name="sms" id="sms"  value="1">
                                    <span>sms</span>
                                </label>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="ckbox">
                                    <input type="checkbox" class="mb-2" name="call" id="call"  value="1">
                                    <span>call</span>
                                </label>
                            </div>

                            <div class="form-group col-md-12">
                                <h5>
                                    {{ trans('packages.additional_data') }}
                                </h5>
                                <hr>
                            </div>

                            <div class="form-group col-md-6" id="number_rooms" style="display: none">
                                <label for="exampleInputEmail1">{{ trans('clothes.number_rooms') }}</label>
                                <input type="text" class="form-control"  name="number_rooms" >
                            </div>

                            <div class="form-group col-md-6" id="swimming_pool" style="display: none">
                                <label class="form-label"> {{ trans('clothes.swimming_pool') }} :</label>
                                <select name="swimming_pool"  class="form-control " >
                                    <option value=""> </option>
                                    <option value="1"> يوجد</option>
                                    <option value="0">لا يوجد</option>

                                </select>
                            </div>

                            <div class="form-group col-md-6" id="Jim" style="display: none">
                                <label class="form-label"> {{ trans('clothes.Jim') }} :</label>
                                <select name="Jim"  class="form-control " >
                                    <option value=""> </option>
                                    <option value="1"> يوجد</option>
                                    <option value="0">لا يوجد</option>

                                </select>
                            </div>

                            <div class="form-group col-md-6" id="year" style="display: none">
                                <label for="exampleInputEmail1">{{ trans('clothes.year') }}</label>
                                <input type="text" class="form-control"  name="year" >
                            </div>

                            <div class="form-group col-md-6" id="cere" style="display: none">
                                <label class="form-label"> {{ trans('clothes.cere') }} :</label>
                                <select name="cere"  class="form-control " >
                                    <option value=""> </option>
                                    <option value="0"> اتوماتك</option>
                                    <option value="1">عادي</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6" id="number_cylinders" style="display: none">
                                <label for="exampleInputEmail1">{{ trans('clothes.number_cylinders') }}</label>
                                <input type="number" class="form-control"  name="number_cylinders" >
                            </div>

                            <div class="form-group col-md-6" id="salary" style="display: none">
                                <label for="exampleInputEmail1">{{ trans('clothes.salary') }}</label>
                                <input type="number" class="form-control"  name="salary" >
                            </div>




                            <div class="form-group col-md-6" style="display: none" id="working_condition">
                                <label class="form-label"> {{ trans('clothes.working_condition') }} :</label>
                                <select name="working_condition"  class="form-control " >
                                    <option value=""> </option>
                                    <option value="0"> مستعمل </option>
                                    <option value="1">جديد</option>

                                </select>
                            </div>

                            <div class="form-group col-md-6" id="biography" style="display: none">
                                <label for="exampleInputEmail1">{{ trans('clothes.biography') }}</label>
                                <input type="file" class="form-control"  name="biography" >
                            </div>


                            @php
                                $brand=\App\Models\Item::where('type',1)->get();
                            @endphp
                            <div class="form-group col-md-6" style="display: none" id="brand_id">
                                <label class="form-label"> {{ trans('clothes.brand_id') }} :</label>
                                <select name="brand_id"  class="form-control " >
                                    <option value=""> </option>
                                    @foreach($brand as $b)
                                        <option value="{{$b->id}} "> {{$b->title_ar}} </option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $educational_level=\App\Models\Item::where('type',2)->get();

                            @endphp
                            <div class="form-group col-md-6" style="display: none" id="educational_level_id">
                                <label class="form-label"> {{ trans('clothes.educational_level_id') }} :</label>
                                <select name="educational_level_id"  class="form-control " >
                                    <option value=""> </option>

                                    @foreach($educational_level as $e)
                                        <option value="{{$e->id}} "> {{$e->title_ar}} </option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $specialization=\App\Models\Item::where('type',3)->get();

                            @endphp
                            <div class="form-group col-md-6" style="display: none" id="specialization_id">
                                <label class="form-label"> {{ trans('clothes.specialization_id') }} :</label>
                                <select name="specialization_id"  class="form-control " >
                                    <option value=""> </option>

                                    @foreach($specialization as $s)
                                        <option value="{{$s->id}} "> {{$s->title_ar}} </option>
                                    @endforeach
                                </select>
                            </div>


                            @php
                                $subjects=\App\Models\Item::where('type',4)->get();

                            @endphp
                            <div class="form-group col-md-6" style="display: none" id="subjects_id">
                                <label class="form-label"> {{ trans('clothes.subjects_id') }} :</label>
                                <select name="subjects_id"  class="form-control " >
                                    <option value=""> </option>
                                    @foreach($subjects as $su)
                                        <option value="{{$su->id}} "> {{$su->title_ar}} </option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $animal_type=\App\Models\Item::where('type',5)->get();

                            @endphp
                            <div class="form-group col-md-6" style="display: none" id="animal_type_id">
                                <label class="form-label"> {{ trans('clothes.animal_type_id') }} :</label>
                                <select name="animal_type_id"  class="form-control " >
                                    <option value=""> </option>
                                    @foreach($animal_type as $a)

                                        <option value="{{$a->id}} "> {{$a->title_ar}} </option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $fashion_type=\App\Models\Item::where('type',6)->get();

                            @endphp
                            <div class="form-group col-md-6" style="display: none" id="fashion_type_id">
                                <label class="form-label"> {{ trans('clothes.fashion_type_id') }} :</label>
                                <select name="fashion_type_id"  class="form-control " >
                                    <option value=""> </option>

                                    @foreach($fashion_type as $f)
                                        <option value="{{$f->id}} "> {{$f->title_ar}} </option>
                                    @endforeach
                                </select>
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
    </script>
@endsection
