<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- FAVICON -->
<link rel="shortcut icon" type="image/x-icon"
      href="{{ asset(isset($setting->where('key','fav_icon')->first()->value) ? $setting->where('key','fav_icon')->first()->value : null)}}"/>

<!-- TITLE -->
<title>@yield('title')</title>
<!-- BOOTSTRAP CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@if(lang() == 'ar')
    <!-- STYLE CSS *** remove rtl to switch *** -->
    <link href="{{asset('assets/admin/assets/css-rtl/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css-rtl/skin-modes.css" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css-rtl/dark-style.css" rel="stylesheet"/>
@else
    <!-- STYLE CSS *** remove rtl to switch *** -->
    <link href="{{asset('assets/admin/assets/css/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css/skin-modes.css" rel="stylesheet"/>
    <link href="{{asset('assets/admin')}}/assets/css/dark-style.css" rel="stylesheet"/>
@endif

@if(lang() == 'ar')
    {{--    <!-- SIDE-MENU CSS *** remove rtl to switch *** -->--}}
    <link href="{{asset('assets/admin')}}/assets/css-rtl/sidemenu.css" rel="stylesheet">
@else
    <link href="{{asset('assets/admin')}}/assets/css/sidemenu.css" rel="stylesheet">

@endif
<!--PERFECT SCROLL CSS-->
<link href="{{asset('assets/admin')}}/assets/plugins/p-scroll/perfect-scrollbar.css" rel="stylesheet"/>

<!-- CUSTOM SCROLL BAR CSS-->
<link href="{{asset('assets/admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet"/>

<!--- FONT-ICONS CSS -->
<link href="{{asset('assets/admin/assets/css/icons.css')}}" rel="stylesheet"/>

<!-- SIDEBAR CSS -->
<link href="{{asset('assets/admin')}}/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

<!-- COLOR SKIN CSS -->
<link id="theme" rel="stylesheet" type="text/css" media="all"
      href="{{asset('assets/admin')}}/assets/colors/color1.css"/>

{{--  ckeditor  --}}
<link rel="stylesheet" href="{{ asset('assets/dropify/css/dropify.min.css')}}">


{{--  ckeditor  --}}

<!-- Switcher CSS -->
<link href="{{asset('assets/admin')}}/assets/switcher/css/switcher-rtl.css" rel="stylesheet">
<link href="{{asset('assets/admin')}}/assets/switcher/demo.css" rel="stylesheet">

<script defer src="{{asset('assets/admin')}}/assets/iconfonts/font-awesome/js/brands.js"></script>
<script defer src="{{asset('assets/admin')}}/assets/iconfonts/font-awesome/js/solid.js"></script>
<script defer src="{{asset('assets/admin')}}/assets/iconfonts/font-awesome/js/fontawesome.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet"/>

<link href="{{ asset('assets/admin/assets/css/select2.min.css') }}" rel="stylesheet"/>
<link rel="stylesheet" href="{{asset('assets/website/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/font-awesome-v6/css/all.min.css')}}">
<script defer src="{{asset('assets/website/js/all.min.js')}}"></script>
<script defer src="{{asset('assets/font-awesome-v6/js/all.min.js')}}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.4/dist/sweetalert2.min.css">

<link rel="stylesheet" href="{{ asset('assets/fileUpload/fileUpload.css') }}">

{{-- toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
      integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('css')

<link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">

<link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/autofill/2.7.0/css/autoFill.bootstrap4.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/2.0.4/css/colReorder.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/datetime/1.5.4/css/dataTables.dateTime.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/fixedcolumns/5.0.4/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/fixedheader/4.0.1/css/fixedHeader.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/keytable/2.12.1/css/keyTable.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowgroup/1.5.1/css/rowGroup.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/searchbuilder/1.8.1/css/searchBuilder.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/searchpanes/2.3.3/css/searchPanes.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/2.1.0/css/select.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/staterestore/1.4.1/css/stateRestore.bootstrap4.min.css" rel="stylesheet">


<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css"/>

<link rel="stylesheet" href="{{asset('richtexteditor')}}/rte_theme_default.css" />

