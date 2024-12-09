@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />

    <link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/morris.js/morris.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
                            <h4 class="content-title mb-0 my-auto">{{trans('users_admin.Dashboard')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> / {{trans('shops.shops')}}</span>
                                            <span class="text-muted mt-1 tx-13 mr-2 mb-0"> / {{trans('shops.Profile')}}</span>
						</div>
					</div>

				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-lg-4">
						<div class="card mg-b-20">
							<div class="card-body">
								<div class="pl-0">
									<div class="main-profile-overview">
										<div class="main-img-user profile-user">

											<img alt="" src="{{url('').'/assets/tmp/'.(!empty($shops->avatar) ? $shops->avatar:'logo/logo.png')}}" >

										</div>
										<div class="d-flex justify-content-between mg-b-20">
											<div>
												<h5 class="main-profile-name">
                                                    {{$shops->first_name . ' ' . $shops->last_name}}
{{--                                                        {{ !empty($shops->first_name) ? $shops->first_name:'' }}--}}


                                                </h5>

											</div>
										</div>
										<h6>Bio</h6>
{{--										<div class="main-profile-bio">--}}
{{--                                            @if (App::getLocale() == 'en')--}}
{{--                                                {{ !empty($shops->description_en) ? $shops->description_en:'' }}--}}
{{--                                            @else--}}
{{--                                                {{ !empty($shops->description_ar) ? $shops->description_ar:'' }}--}}
{{--                                            @endif--}}
{{-- 										</div><!-- main-profile-bio -->--}}

										<hr class="mg-y-30">
{{--										<label class="main-content-label tx-13 mg-b-20">Social</label>--}}
										<div class="main-profile-social-list">
											<div class="media">

												<div class="media-body">
													<h6>{{trans('shops.phone')}}</h6> <a href="#">{{ !empty($shops->mobile_number) ? $shops->mobile_number:'' }}</a>
												</div>
											</div>
											<div class="media">

												<div class="media-body">
													<h6>{{trans('shops.email')}}</h6> <a href="#">{{ !empty($shops->email) ? $shops->email:'' }}</a>
												</div>
											</div>
											<div class="media">

												<div class="media-body">
													<h6>{{trans('shops.created_at')}}</h6> <a href="#">{{ !empty($shops->created_at) ? $shops->created_at:'' }}</a>
												</div>
											</div>

										</div>


									</div><!-- main-profile-overview -->
								</div>
							</div>
						</div>

                                <div class="card mg-b-20">
                                    <div class="card-body">
                                        <div class="main-content-label mg-b-5">
                                            {{trans('home.chart')}}
                                        </div>
                                        <p class="mg-b-20">{{trans('home.number_chart')}}</p>
                                        <div id="echart1" class="ht-300"></div>
                                    </div>
                                </div>


					</div>
					<div class="col-lg-8">
						<div class="row row-sm">
							<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-body">
										<div class="counter-status d-flex md-mb-0">
											<div class="counter-icon bg-primary-transparent">
												<i class="icon-layers text-primary"></i>
											</div>
											<div class="mr-auto">
												<h5 class="tx-13">{{trans('shops.orders')}}</h5>
												<h2 class="mb-0 tx-22 mb-1 mt-1">{{$order->count()}}</h2>
												<p class="text-muted mb-0 tx-11"><i class="si si-arrow-up-circle text-success mr-1"></i>increase</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-body">
										<div class="counter-status d-flex md-mb-0">
											<div class="counter-icon bg-danger-transparent">
												<i class="icon-paypal text-danger"></i>
											</div>
											<div class="mr-auto">
												<h5 class="tx-13">{{trans('shops.Revenue')}}</h5>
												<h2 class="mb-0 tx-22 mb-1 mt-1">{{$order->sum('total_cost')}}</h2>
												<p class="text-muted mb-0 tx-11"><i class="si si-arrow-up-circle text-success mr-1"></i>increase</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-body">
										<div class="counter-status d-flex md-mb-0">
											<div class="counter-icon bg-success-transparent">
												<i class="icon-rocket text-success"></i>
											</div>
											<div class="mr-auto">
												<h5 class="tx-13">{{trans('shops.Product')}}</h5>
												<h2 class="mb-0 tx-22 mb-1 mt-1">{{$product->count()}}</h2>
												<p class="text-muted mb-0 tx-11"><i class="si si-arrow-up-circle text-success mr-1"></i>increase</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="tabs-menu ">
									<!-- Tabs -->
									<ul class="nav nav-tabs profile navtab-custom panel-tabs">
										<li class="active">
											<a href="#home" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i class="las la-user-circle tx-16 mr-1"></i></span> <span class="hidden-xs">{{trans('shops.Store_data')}}</span> </a>
										</li>
										<li class="">
											<a href="#profile" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="las la-images tx-15 mr-1"></i></span> <span class="hidden-xs">{{trans('shops.Product')}}</span> </a>
										</li>

									</ul>
								</div>
								<div class="tab-content border-left border-bottom border-right border-top-0 p-4">
									<div class="tab-pane active" id="home">

                                        <div class="alert alert-success" role="alert">
                                            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                            <span class="alert-inner--text"><strong>{{trans('shops.account_number')}}</strong>

                                                 {{ ($shops->id)  }}

                                            </span>
                                        </div>
                                        <div class="alert alert-success" role="alert">
                                            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                            <span class="alert-inner--text"><strong>{{trans('shops.points')}}</strong>

                                                {{ !empty($shops->points) ? $shops->points: trans('shops.null') }}
                                            </span>
                                        </div>



                                        <div class="alert alert-success" role="alert">
                                            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                            <span class="alert-inner--text"><strong>{{trans('shops.kitchen_case')}}</strong>

                                                @if ($shops->status == 'active')
                                                    {{trans('shops.active')}}
                                                @elseif($shops->status == 'pending_activation')
                                                    {{trans('shops.pending_activation')}}
                                                @else
                                                    {{trans('shops.Inactive')}}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="alert alert-success" role="alert">
                                            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                            <span class="alert-inner--text"><strong>{{trans('shops.activation_code')}}</strong>
                                                {{ !empty($shops->activation_code) ? $shops->activation_code: trans('shops.null') }}
                                            </span>
                                        </div>

                                        <div class="alert alert-success" role="alert">
                                            <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                            <span class="alert-inner--text"><strong>{{trans('shops.Expiry_date')}}</strong>

                                                {{ !empty($shops->exp_date) ? $shops->exp_date: trans('shops.null') }}
                                            </span>
                                        </div>



									</div>
									<div class="tab-pane" id="profile">
										<div class="row">


                                            <div class="col-xl-12">
                                                <div class="card">

                                                    <div class="card-body">
                                                        <div class="table-responsive hoverable-table">
                                                            <table class="table table-hover" id="example1" data-page-length='50' style=" text-align: center;">
                                                                <thead>
                                                                <tr>
                                                                    <th class="wd-10p border-bottom-0">#</th>
                                                                    <th class="wd-15p border-bottom-0">{{trans('products.image')}}</th>
                                                                    <th class="wd-20p border-bottom-0">{{trans('products.title')}}</th>
                                                                    <th class="wd-20p border-bottom-0">{{trans('products.category')}}</th>
{{--                                                                    <th class="wd-15p border-bottom-0">{{trans('products.sections')}}</th>--}}
                                                                    <th class="wd-15p border-bottom-0">{{trans('products.price')}}</th>
                                                                    <th class="wd-15p border-bottom-0">{{trans('shops.Quantity')}}</th>

                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $i = 0; ?>
                                                                @foreach ($product as $x)
                                                                    <?php $i++; ?>
                                                                    <tr>
                                                                        <td>{{ $i }}</td>

                                                                        <td><img src="{{url('').'/assets/tmp/'.$x->image}}" style="width: 40px;height: 40px"></td>
                                                                        {{--                                    <td>{{ !empty($x->image) ? $x->image:'' }}</td>--}}

                                                                        @if (App::getLocale() == 'en')
                                                                            <td>{{ !empty($x->title_en) ? $x->title_en:'' }}</td>
                                                                            <td>{{ !empty($x->app_users->category->title_en) ? $x->app_users->category->title_en:'' }}</td>
                                                                            <td>{{ !empty($x->section->title_en) ? $x->section->title_en:'' }}</td>


                                                                            {{--                                        <td>{{ !empty($x->app_user->address->governorates->title_en) ? $x->app_user->address->governorates->title_en:'' }}</td>--}}
                                                                            {{--                                        <td>{{ !empty($x->app_user->address->city->title_en) ? $x->app_user->address->city->title_en:'' }}</td>--}}

                                                                        @else

                                                                            <td>{{ !empty($x->title_ar) ? $x->title_ar:'' }}</td>
                                                                            <td>{{ !empty($x->categories->title_ar) ? $x->categories->title_ar:'' }}</td>
{{--                                                                            <td>{{ !empty($x->section->title_ar) ? $x->section->title_ar:'' }}</td>--}}


                                                                            {{--                                        <td>{{ !empty($x->app_user->address->governorates->title_ar) ? $x->app_user->address->governorates->title_ar:'' }}</td>--}}
                                                                            {{--                                        <td>{{ !empty($x->app_user->address->city->title_ar) ? $x->app_user->address->city->title_ar:'' }}</td>--}}

                                                                        @endif

                                                                        <td>{{ !empty($x->price) ? $x->price:'' }}</td>
                                                                        <td>{{ !empty($x->quntaty) ? $x->quntaty: 0 }}</td>


                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
										</div>
									</div>
									<div class="tab-pane" id="settings">
										<form role="form">
											<div class="form-group">
												<label for="FullName">Full Name</label>
												<input type="text" value="John Doe" id="FullName" class="form-control">
											</div>
											<div class="form-group">
												<label for="Email">Email</label>
												<input type="email" value="first.last@example.com" id="Email" class="form-control">
											</div>
											<div class="form-group">
												<label for="Username">Username</label>
												<input type="text" value="john" id="Username" class="form-control">
											</div>
											<div class="form-group">
												<label for="Password">Password</label>
												<input type="password" placeholder="6 - 15 Characters" id="Password" class="form-control">
											</div>
											<div class="form-group">
												<label for="RePassword">Re-Password</label>
												<input type="password" placeholder="6 - 15 Characters" id="RePassword" class="form-control">
											</div>
											<div class="form-group">
												<label for="AboutMe">About Me</label>
												<textarea id="AboutMe" class="form-control">Loren gypsum dolor sit mate, consecrate disciplining lit, tied diam nonunion nib modernism tincidunt it Loretta dolor manga Amalia erst volute. Ur wise denim ad minim venial, quid nostrum exercise ration perambulator suspicious cortisol nil it applique ex ea commodore consequent.</textarea>
											</div>
											<button class="btn btn-primary waves-effect waves-light w-md" type="submit">Save</button>
										</form>
									</div>
								</div>
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
    <script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!-- Internal Select2 js-->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <!--Internal Echart Plugin -->
    <script src="{{URL::asset('assets/plugins/echart/echart.js')}}"></script>

    <script>

        $(function(e) {
            'use strict'
            /*----Echart2----*/
            var chartdata = [{
                name: '{{trans('home.products')}}',
                type: 'bar',
                barMaxWidth: 20,
                // data: [0,1,5,25,30,40,100]
                data: @json($products)
            },  {
                name: '{{trans('home.orders')}}',
                type: 'bar',
                barMaxWidth: 20,
                // data: [3,50,20,25,50,20,80]
                data: @json($orders)
            }];
            var chart = document.getElementById('echart1');
            var barChart = echarts.init(chart);
            var option = {
                valueAxis: {
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(171, 167, 167,0.2)']
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['rgba(171, 167, 167,0.2)']
                        }
                    }
                },
                grid: {
                    top: '6',
                    right: '0',
                    bottom: '17',
                    left: '25',
                },
                xAxis: {
                    data: @json($date),
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#5f6d7a'
                    }
                },
                tooltip: {
                    trigger: 'axis',
                    position: ['35%', '32%'],
                },
                yAxis: {
                    splitLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#5f6d7a'
                    }
                },
                series: chartdata,
                color: ['#285cf7', '#f7557a' ]
            };
            barChart.setOption(option);





            /*----BarChartEchart----*/
            var echartBar = echarts.init(document.getElementById('index'), {
                color: ['#285cf7', '#f7557a'],
                categoryAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#888180'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['rgba(171, 167, 167,0.2)']
                        }
                    }
                },
                grid: {
                    x: 40,
                    y: 20,
                    x2: 40,
                    y2: 20
                },
                valueAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#888180'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(255,255,255,0.1)']
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['rgba(171, 167, 167,0.2)']
                        }
                    }
                },
            });
            echartBar.setOption({
                tooltip: {
                    trigger: 'axis',
                    position: ['35%', '32%'],
                },
                legend: {
                    data: ['New Account', 'Expansion Account']
                },
                toolbox: {
                    show: false
                },
                calculable: false,
                xAxis: [{
                    type: 'category',
                    data: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#5f6d7a'
                    }
                }],
                yAxis: [{
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(171, 167, 167,0.2)'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#5f6d7a'
                    }
                }],
                series: [{
                    name: 'View Price',
                    type: 'bar',
                    data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3],
                    markPoint: {
                        data: [{
                            type: 'max',
                            name: ''
                        }, {
                            type: 'min',
                            name: ''
                        }]
                    },
                    markLine: {
                        data: [{
                            type: 'average',
                            name: ''
                        }]
                    }
                }, {
                    name: ' Purchased Price',
                    type: 'bar',
                    // data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],
                    markPoint: {
                        data: [{
                            name: 'Purchased Price',
                            value: 182.2,
                            xAxis: 7,
                            // yAxis: 183,
                        }, {
                            name: 'Purchased Price',
                            value: 2.3,
                            xAxis: 11,
                            // yAxis: 3
                        }]
                    },
                    markLine: {
                        data: [{
                            type: 'average',
                            name: ''
                        }]
                    }
                }]
            });
        });

    </script>
@endsection
