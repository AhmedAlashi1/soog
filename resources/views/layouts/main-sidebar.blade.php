<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href=""><img src="{{asset('ocean1.png') ?? ""}}" class="main-logo" alt="logo" style="width: 150px;"></a>
        <a class="desktop-logo logo-dark active" href=""><img src="{{asset('ocean.png') ?? ""}}" class="main-logo dark-theme" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href=""><img src="{{asset('ocean.png') ?? ""}}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href=""><img src="{{asset('ocean.png') ?? ""}}" class="logo-icon dark-theme" alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround" src="{{asset('logo.png') ?? ""}}"><span
                            class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0"> {{ Auth::user()->name ?? ""}}</h4>
                    <span class="mb-0 text-muted">{{ Auth::user()->email ?? ""}}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">Main</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url(\Illuminate\Support\Facades\App::getLocale().'/home') }}">
                    <img class="side-menu__icon"
                         src="{{url('https://img.icons8.com/fluency/48/000000/dashboard-layout.png')}}"
                         style=" width: 30px; height: 30px;"/>
                    <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.dashboard')}}</span>
                </a>
            </li>
            <li class="side-item side-item-category">General</li>
            @can('member-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin') }}">
                        <img class="side-menu__icon" src="{{url('https://img.icons8.com/external-icongeek26-outline-colour-icongeek26/64/000000/external-monitor-online-education-icongeek26-outline-colour-icongeek26-1.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.users')}}</span>
                    </a>
                </li>
            @endcan
            @can('role-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('roles.index') }}">
                        <img class="side-menu__icon" src="{{url('https://img.icons8.com/nolan/344/service.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.roles')}}</span>
                    </a>
                </li>
            @endcan
            @can('categories-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('category') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/nolan/344/categorize.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.cats')}}</span>
                    </a>
                </li>
            @endcan
            @can('categories-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('item') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/nolan/344/categorize.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.item')}}</span>
                    </a>
                </li>
            @endcan
            @can('product-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('advertisements') }}?type=0">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/external-icongeek26-outline-gradient-icongeek26/64/null/external-ads-ads-icongeek26-outline-gradient-icongeek26-1.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.advertisements')}}</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('advertisements') }}?type=1">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/external-icongeek26-outline-gradient-icongeek26/64/null/external-ads-ads-icongeek26-outline-gradient-icongeek26-6.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.advertisements_installed')}}</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('advertisements') }}?type=2">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/external-icongeek26-outline-gradient-icongeek26/64/null/external-ads-ads-icongeek26-outline-gradient-icongeek26-9.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.advertisements_featured')}}</span>
                    </a>
                </li>
            @endcan

{{--            @can('discountCodes-view')--}}
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('coupons') }}">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/nolan/344/online-shop-sale.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.coupons')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
            @can('ads-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('ads') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/external-icongeek26-outline-colour-icongeek26/344/external-ads-ads-icongeek26-outline-colour-icongeek26-7.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.ads')}}</span>
                    </a>
                </li>
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('ads') }}?type=international">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/external-icongeek26-outline-colour-icongeek26/344/external-ads-ads-icongeek26-outline-colour-icongeek26-7.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.adsinternational')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
            @endcan

            @can('customer-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('app_user') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/nolan/344/customer-insight.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.appusers')}}</span>
                    </a>
                </li>
            @endcan
{{--            @can('order-view')--}}
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('orders') }}">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/ultraviolet/344/deliver-food.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.order')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--            @can('archives-view')--}}
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('archives') }}">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/nolan/512/museum.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('category.content_titlegg')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--            @can('contact-view')--}}
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('contact') }}">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/nolan/344/email.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.contact')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
            @can('paymentMethod-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('payment') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/nolan/344/card-security.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.payment')}}</span>
                    </a>
                </li>
            @endcan
{{--            @can('Worktime-view')--}}
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('times') }}">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/external-itim2101-blue-itim2101/344/external-work-time-time-management-itim2101-blue-itim2101-1.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.times')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--            @can('deliveryHour-view')--}}
{{--                <li class="slide">--}}
{{--                    <a class="side-menu__item" href="{{ route('deliveryTypes') }}">--}}
{{--                        <img class="side-menu__icon"--}}
{{--                             src="{{url('https://img.icons8.com/ultraviolet/344/deliver-food.png')}}"--}}
{{--                             style=" width: 30px; height: 30px;"/>--}}
{{--                        <span class="side-menu__label"--}}
{{--                              style=" font-weight: bold;">{{trans('menu.deliveryTypes')}}</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
            @can('packages-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('packages.index') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/ultraviolet/344/deliver-food.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.packages')}}</span>
                    </a>
                </li>
            @endcan
            @can('region-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('country') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/external-icongeek26-outline-colour-icongeek26/344/external-ads-ads-icongeek26-outline-colour-icongeek26-7.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('ads.title_country')}}</span>
                    </a>
                </li>
            @endcan
            @can('region-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('countries') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/nolan/344/region-code.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.regions')}}</span>
                    </a>
                </li>
            @endcan

            <!--@can('region-view')-->
            <!--    <li class="slide">-->
            <!--        <a class="side-menu__item" href="{{ route('cities') }}">-->
            <!--            <img class="side-menu__icon"-->
            <!--                 src="{{url('https://img.icons8.com/nolan/344/region-code.png')}}"-->
            <!--                 style=" width: 30px; height: 30px;"/>-->
            <!--            <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.cities')}}</span>-->
            <!--        </a>-->
            <!--    </li>-->
            <!--@endcan-->
            @can('notification-view')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('notify2') }}">
                        <img class="side-menu__icon"
                             src="{{url('https://img.icons8.com/nolan/344/message-group.png')}}"
                             style=" width: 30px; height: 30px;"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.Alert')}}</span>
                    </a>
                </li>

{{--            <li class="slide">--}}
{{--                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">--}}
{{--                    <img class="side-menu__icon" style=" width: 30px; height: 30px;"--}}
{{--                         src="{{url('https://img.icons8.com/nolan/344/message-group.png')}}"/>--}}
{{--                    <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.Alert')}}</span>--}}
{{--                    <i class="angle fe fe-chevron-down"></i></a>--}}
{{--                <ul class="slide-menu">--}}
{{--                    <li><a class="slide-item" style=" font-weight: bold;"--}}
{{--                           href="{{ route('notify2') }}">{{trans('menu.Instant_Notifications')}}</a></li>--}}
{{--                    <li><a class="slide-item" style=" font-weight: bold;"--}}
{{--                           href="{{ route('notification') }}">{{trans('menu.Scheduled_notifications')}}</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}
            @endcan
{{--            @can('report-view')--}}
{{--            <li class="slide">--}}
{{--                <a class="side-menu__item" href="{{ route('reports') }}">--}}
{{--                    <img class="side-menu__icon"--}}
{{--                         src="{{url('https://img.icons8.com/external-gradients-pause-08/512/external-bar-business-gradients-pause-08.png')}}"--}}
{{--                         style=" width: 30px; height: 30px;"/>--}}
{{--                    <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.reports')}}</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @endcan--}}
            @can('setting-view')
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
                        <img class="side-menu__icon" style=" width: 30px; height: 30px;"
                             src="{{url('https://img.icons8.com/nolan/64/settings--v1.png')}}"/>
                        <span class="side-menu__label" style=" font-weight: bold;">{{trans('menu.setting')}}</span>
                        <i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" style=" font-weight: bold;"
                               href="{{ route('setting.global') }}">{{trans('menu.global')}}</a></li>
                        <li><a class="slide-item" style=" font-weight: bold;"
                               href="{{ route('setting.social') }}">{{trans('menu.social')}}</a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
