<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/web') }}/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/web') }}/css/bootstrap.min.css">
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('assets/web') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('assets/web') }}/css/owl.theme.default.min.css">
    <!-- slick plugin -->
    <link href="{{ asset('assets/web/css/slick-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/web/css/slick.css') }}" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ getFile(isset($setting->logo) ? $setting->logo : null) }}" />


    @if(lang() == 'ar')
        <link rel="stylesheet" href="{{asset('assets/web')}}/css/style.css">
    @else
        <link rel="stylesheet" href="{{asset('assets/web')}}/css/ltr.css">
    @endif


    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        .linkactive {
            color: var(--main-color) !important;
        }
    </style>
</head>
