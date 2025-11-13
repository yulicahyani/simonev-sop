@php
$get_all_menu = App\Services\MenuService::getNavBarMenuWithCategory();
// dd($get_all_menu);
@endphp

<div class="container-xxl d-flex align-items-center">
    <!--begin::Heaeder menu toggle-->
    <div class="d-flex topbar align-items-center d-lg-none ms-n2 me-3" title="Show aside menu">
        <div class="btn btn-icon btn-active-light-primary btn-custom w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
            <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
            <span class="svg-icon svg-icon-1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
                    <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
    </div>
    <!--end::Heaeder menu toggle-->
    <!--begin::Header Logo-->
    <div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
        <a href="{{route('backend.beranda')}}">
            <img alt="Logo" src="{{asset('images/logo/logo2_light.png')}}" class="logo-default" width="120" height="100"/>
            <img alt="Logo" src="{{asset('images/logo/logo2_no_background.png')}}" class="logo-sticky" width="120" height="100" />
        </a>
    </div>
    <!--end::Header Logo-->
    <!--begin::Wrapper-->
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
        <!--begin::Navbar-->
        <div class="d-flex align-items-stretch" id="kt_header_nav">
            <!--begin::Menu wrapper-->
            <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-400 fw-semibold my-5 my-lg-0 align-items-stretch px-2 px-lg-0" id="#kt_header_menu" data-kt-menu="true">

                    @foreach ($get_all_menu as $item)
                        @if (empty($item['cat_text']))
                            @foreach ($item['menu'] as $menu)
                                @if (isset($menu['can']))
                                    @if (Gate::forUser(session('authUserData'))->allows($menu['can']))
                                    <!--begin:Menu item-->
                                    <div class="menu-item {{ (App\Services\MenuService::isActive($menu['active'])) ? 'here show menu-here-bg' : '' }} me-0 me-lg-2">
                                        <!--begin:Menu link-->
                                        <a href="{{ $menu['url'] }}" class="menu-link py-3"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" 
                                        data-bs-dismiss="click" data-bs-placement="right">
                                            <span class="menu-title">{{ $menu['text'] }}</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->
                                    @endif
                                @endif
                            @endforeach
                        @else
                            @if (isset($item['can']))
                                @if (Gate::forUser(session('authUserData'))->allows($item['can']))
                                    <!--begin:Menu item-->
                                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item {{ (App\Services\MenuService::isActive($item['active'])) ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                        <!--begin:Menu link-->
                                        <span class="menu-link py-3">
                                            <span class="menu-title">{{$item['cat_text']}}</span>
                                            <span class="menu-arrow d-lg-none"></span>
                                        </span>
                                        <!--end:Menu link-->
                                        <!--begin:Menu sub-->
                                        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">

                                            @foreach ($item['menu'] as $menu)

                                                @if (isset($menu['can']))
                                                    @if (Gate::forUser(session('authUserData'))->allows($menu['can']))

                                                        @if (isset($menu['menu']))
                                                            <!--begin:Menu item-->
                                                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start" class="menu-item {{ (App\Services\MenuService::isActive($menu['active'])) ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                                                <!--begin:Menu link-->
                                                                <span class="menu-link py-3">
                                                                    <span class="menu-icon">
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                                                                        <span class="svg-icon svg-icon-3">
                                                                            <i class="bi bi-{{ $menu['icon'] }}" style="font-size: 18px;"></i>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </span>
                                                                    <span class="menu-title">{{$menu['cat_text']}}</span>
                                                                    <span class="menu-arrow"></span>
                                                                </span>
                                                                <!--end:Menu link-->
                                                                <!--begin:Menu sub-->
                                                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">

                                                                    @foreach ($menu['menu'] as $sub_menu)

                                                                        @if (isset($sub_menu['can']))
                                                                            @if (Gate::forUser(session('authUserData'))->allows($sub_menu['can']))
                                                                                <!--begin:Menu item-->
                                                                                <div class="menu-item {{ (App\Services\MenuService::isActive($sub_menu['active'])) ? 'here show' : '' }}">
                                                                                    <!--begin:Menu link-->
                                                                                    <a class="menu-link py-3" href="{{ $sub_menu['url'] }}" title="{{ $sub_menu['desc']}}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                                                                        <span class="menu-icon">
                                                                                            <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                                                                                            <span class="svg-icon svg-icon-3">
                                                                                                <i class="bi bi-{{ $sub_menu['icon'] }}" style="font-size: 18px;"></i>
                                                                                            </span>
                                                                                            <!--end::Svg Icon-->
                                                                                        </span>
                                                                                        <span class="menu-title">{{ $sub_menu['text'] }}</span>
                                                                                    </a>
                                                                                    <!--end:Menu link-->
                                                                                </div>
                                                                                <!--end:Menu item-->
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <!--end:Menu sub-->
                                                            </div>
                                                            <!--end:Menu item-->
                                                        @else
                                                        <!--begin:Menu item-->
                                                        <div class="menu-item {{ (App\Services\MenuService::isActive($menu['active'])) ? 'here show' : '' }}">
                                                            <!--begin:Menu link-->
                                                            <a class="menu-link py-3" href="{{ $menu['url'] }}" title="{{ $menu['desc']}}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                                                <span class="menu-icon">
                                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                                                                    <span class="svg-icon svg-icon-3">
                                                                        <i class="bi bi-{{ $menu['icon'] }}" style="font-size: 18px;"></i>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </span>
                                                                <span class="menu-title">{{ $menu['text'] }}</span>
                                                            </a>
                                                            <!--end:Menu link-->
                                                        </div>
                                                        <!--end:Menu item-->
                                                        @endif

                                                        
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                        <!--end:Menu sub-->
                                    </div>
                                    <!--end:Menu item-->
                                @endif
                            @endif
                        @endif
                    @endforeach
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu wrapper-->
        </div>
        <!--end::Navbar-->
        <!--begin::Toolbar wrapper-->
        @include('metronic.backend.partials.toolbar')
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Wrapper-->
</div>