<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
    @include('metronic.backend.partials.head')
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
		<!--begin::Theme mode setup on page load-->
		{{-- <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-theme-mode")) { themeMode = document.documentElement.getAttribute("data-theme-mode"); } else { if ( localStorage.getItem("data-theme") !== null ) { themeMode = localStorage.getItem("data-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-theme", themeMode); }</script> --}}
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header align-items-stretch" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
						<!--begin::Container-->
						@include('metronic.backend.partials.navbar')
						<!--end::Container-->
					</div>
					<!--end::Header-->
					
					<!--begin::Content-->
					@yield('content')
					<!--end::Content-->

					<!--begin::Footer-->
					@include('metronic.backend.partials.footer')
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->


		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{asset('metronic/assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('metronic/assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used by this page)-->
		<script src="{{asset('metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used by this page)-->
		<script src="{{asset('metronic/assets/js/widgets.bundle.js')}}"></script>
		<script src="{{asset('metronic/assets/js/custom/widgets.js')}}"></script>
		<script src="{{asset('metronic/assets/js/custom/apps/chat/chat.js')}}"></script>
		<script src="{{asset('metronic/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
		<script src="{{asset('metronic/assets/js/custom/utilities/modals/create-app.js')}}"></script>
		<script src="{{asset('metronic/assets/js/custom/utilities/modals/create-campaign.js')}}"></script>
		<script src="{{asset('metronic/assets/js/custom/utilities/modals/users-search.js')}}"></script>

        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-clock-timepicker@2.6.4/jquery-clock-timepicker.min.js"></script>

		<!--end::Custom Javascript-->

		{{-- Datatables --}}
		<script src="{{asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>

		{{-- JStree --}}
		<script src="{{asset('metronic/assets/plugins/custom/jstree/jstree.bundle.js')}}"></script>

		{{-- Flatpicker --}}
		<script src="{{ asset('metronic/assets/plugins/custom/flatpickr/flatpickr.min.js') }}"></script>
		<script src="{{ asset('metronic/assets/plugins/custom/flatpickr/l10n/id.js') }}"></script>

        <script>
            window.onload = notif();

            function notif() {
                if ('{{Session::get("message")}}' == 'Success') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: '{!! Session::get("alert-data") !!}',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                if ('{{Session::get("message")}}' == 'Failed') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: '{!! Session::get("alert-data") !!}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }

            function swalDelete(id, message, form) {
                var form = $('#' + form);
                Swal.fire({
                    html: message,
                    icon: "question",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak, Batalkan',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }

            function swalTimer(icon, message, timer) {
                Swal.fire({
                    position: 'center',
                    icon: icon,
                    title: message,
                    buttonsStyling: false,
                    showConfirmButton: false,
                    timer: timer,
                });
            }

            function changerole() {
                var val = $('#change-role').val().split('|');
                var role = val[0];
                var unit_id = val[1];

                var url = "{{url('/changeRole/_role/_unit')}}";
                url = url.replace('_role', role);
                url = url.replace('_unit', unit_id);
                window.location.replace(url);
            }

            function hanyaAngka(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }
        </script>
        @yield('js')
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>