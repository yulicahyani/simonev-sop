<div class="topbar d-flex align-items-stretch flex-shrink-0">
    <!--begin::User-->
    <div class="d-flex align-items-center me-n3 ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <p class="me-2 align-items-center mt-4 fs-8 px-2 py-1 ms-2 mr-5 d-none d-md-block">
            <span class="d-flex align-items-center fs-6 text-white">{{ session('nama') }}</span>
            <span class="d-flex align-items-center fs-8 text-white">{{ session('defaultRole') }} - {{ session('unit') }}</span>
        </p>
        <div class="btn btn-icon btn-active-light-primary btn btn-icon btn-active-light-primary btn-custom w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <img class="h-30px w-30px rounded" src="{{ url("https://ui-avatars.com/api/?name=".session('nama')."&color=388da8&rounded=true") }}" alt="" />
        </div>
        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50px me-5">
                        <img alt="Logo" src="{{ url("https://ui-avatars.com/api/?name=".session('nama')."&color=388da8&rounded=true") }}" />
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Username-->
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-6">{{ session('nama') }}</div>
                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ session('defaultRole') }}</a>
                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ session('unit') }}</a>
                    </div>
                    <!--end::Username-->
                </div>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="https://auth.badungkab.go.id/profile" class="menu-link px-5"> 
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                        <span class="svg-icon svg-icon-3">
                            <i class="bi bi-person" style="font-size: 18px;"></i>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title">Profile Saya</span>
                </a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start">
                <a href="#" class="menu-link px-5">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                        <span class="svg-icon svg-icon-3">
                            <i class="bi bi-person-bounding-box" style="font-size: 18px;"></i>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title">Change Role</span>
                    <span class="menu-arrow"></span>
                </a>
                <!--begin::Menu sub-->
                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                    @foreach (collect(session('roles')) as $role)
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="{{ route('changeRole', ['role' => $role->name, 'unit_id' => encrypt($role->unit_id)]) }}" class="menu-link px-5">
                                @if ($role->role_code == "opd")
                                    {{$role->name}} - {{$role->unit}}
                                @else
                                    {{$role->name}}
                                @endif
                            </a>
                        </div>
                        <!--end::Menu item-->
                    @endforeach
                </div>
                <!--end::Menu sub-->
            </div>
            <!--end::Menu item-->
            
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->

            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="{{route('Logout')}}" class="menu-link px-5">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen002.svg-->
                        <span class="svg-icon svg-icon-3">
                            <i class="bi bi-power" style="font-size: 18px;"></i>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title">Logout</span>
                </a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::User account menu-->
        <!--end::Menu wrapper-->
    </div>
    <!--end::User -->
    <!--begin::Aside mobile toggle-->
    <!--end::Aside mobile toggle-->
</div>