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
<!-- include libraries(jQuery, bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ trans('app_users.Home') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0"> / {{trans('setting.page_title')}}</span>

        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <div class="main-body">
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@elseif( session('delete') )
<div class="alert alert-danger ">
    {{ session('delete') }}
</div>
@endif

<!-- row opened -->
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">

                <h4>{{trans('menu.global')}}</h4>
                <hr>
            </div>

            <div class="card-body">
            @can('setting-view')
                <form action="{{route('setting.update')}}" method="post" autocomplete="off"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @foreach ($settings as $key => $x)
                    <div class="form-group">
                        @if (App::getLocale() == 'en')
                        <label class="col-md-3 control-label" for="site_title">{{ $x->title_en }} : </label>
                        @else
                        <label class="col-md-3 control-label" for="site_title">{{ $x->title_ar }} : </label>
                        @endif
                        @if($x->key_id == 'conditions_ar')
                        <div class="col-md-10">
                            <textarea class="form-control" name="{{ $x->key_id }}" id="summernote1">@if(isset($x->value)){{$x->value}}@endif</textarea>
                        </div>
                        @elseif($x->key_id == 'conditions_en')
                        <div class="col-md-10">
                            <textarea class="form-control" name="{{ $x->key_id }}" id="summernote2">@if(isset($x->value)){{$x->value}}@endif</textarea>
                        </div>
                        @elseif($x->key_id == 'force_update')
{{--                        <div class="col-md-10">--}}
{{--                            <select name="force_update" class="form-control">--}}
{{--                                    <option value="1">نعم</option>--}}
{{--                                    <option value="0">لا</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
                                <div class="col-md-10">
                                    <select   name="force_update" class="form-control" >
                                        <option @if($x->value  == '1') selected="selected" @endif value="1">نعم</option>
                                        <option @if($x->value == '0') selected="selected" @endif value="0">لا</option>
                                    </select>
                                </div>
                        @elseif($x->key_id == 'force_close')
                        <div class="col-md-10">

                                <select   name="force_close" class="form-control" >
                                    <option @if($x->value  == '1') selected="selected" @endif value="1">نعم</option>
                                    <option @if($x->value == '0') selected="selected" @endif value="0">لا</option>
                                </select>
                    

                        </div>

                        @else
                        <div class="col-md-10">
                            <textarea id="site_title" name="{{ $x->key_id }}"
                                class="form-control">@if(isset($x->value)){{$x->value}}@endif</textarea>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    <hr>
                @can('setting-create')
                    <button type="submit" class="btn btn-info-gradient btn-block col-sm-2">
                        <a href="#" style="font-weight: bold; color: beige;">{{trans('setting.edit')}}</a>
                    </button>
                @endcan
                </form>
                @endcan
            </div>
        </div>
    </div>
    <!--/div-->
</div>
    </div>
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
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<!-- Internal Modal js-->
<script src="{{ URL::asset('assets/js/modal.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote1').summernote({
                height: 300,
                focus: true
            });
            $('#summernote2').summernote({
                height: 300,
                focus: true
            });
        });
    </script>


@endsection
