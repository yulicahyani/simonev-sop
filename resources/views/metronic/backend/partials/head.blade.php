<head>
    <base href=""/>
    <title>SIMONEV-SOP - Sistem Informasi Konsultasi, Monitoring & Evaluasi SOP Pemerintah Kabupaten Badung</title>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="SIMONEV-SOP" />
    <meta property="og:url" content="{{route('backend.beranda')}}" />
    <meta property="og:site_name" content="SIMONEV-SOP" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ asset('images/logo/logo1_white_background.png')}}" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{asset('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets(used by this page)-->
    <link href="{{asset('metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    
    <!--begin::Others-->
    <link href="{{ asset('metronic/assets/plugins/custom/jstree/jstree.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jquery-datetime-picker@2.5.11/jquery.datetimepicker.min.css" rel="stylesheet">
    <link href="{{ asset('metronic/assets/plugins/custom/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Others-->

    <style>
    </style>

    @yield('css')

</head>