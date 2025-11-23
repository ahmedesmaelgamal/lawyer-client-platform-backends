<!doctype html>
<html lang="ar" dir="rtl">

<head>
    @include('admin/layouts/head')
    @include('admin/layouts/css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="app sidebar-mini">

    <!-- Start Switcher -->
    {{-- @include('admin/layouts/switcher') --}}
    <!-- End Switcher -->

    <!-- GLOBAL-LOADER -->
    @include('admin/layouts/loader')
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
            <!--APP-SIDEBAR-->
            @include('admin/layouts/main-sidebar')
            <!--/APP-SIDEBAR-->

            <!-- Header -->
            @include('admin/layouts/main-header')
            <!-- Header -->
            <!--Content-area open -->
            <div class="app-content">
                <div class="side-app">

                    <!-- PAGE-HEADER -->
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">
                                {{ trns('welcome_back') }} {{ Auth::guard('admin')->user()->name }}
                                <i class="fas fa-heart text-orange"></i>
                            </h1>

                            <div style="direction: rtl; font-size: 16px; margin-top: 10px;">
                                <span>{{ trns('home') }}</span>
                                <span style="margin: 0 5px;">/</span>
                                <span>@yield('page_name')</span>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE-HEADER END -->
                    @yield('content')
                </div>
                <!-- End Page -->
            </div>
            <!-- CONTAINER END -->
        </div>
        <!-- SIDE-BAR -->

        <!-- FOOTER -->
        @include('admin/layouts/footer')
        <!-- FOOTER END -->
    </div>
    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up mt-4"></i></a>

    @include('admin/layouts/scripts')
    @yield('ajaxCalls')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const darkModeToggle = document.getElementById('darkModeBtn');

            // Function to toggle dark mode
            function toggleDarkMode() {
                document.body.classList.toggle('dark-mode');
                document.body.classList.toggle('dark-menu');

                // Update the stored value in localStorage
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    localStorage.removeItem('darkMode');
                }
            }

            // Event listener for the button
            darkModeToggle.addEventListener('click', toggleDarkMode);

            // Check localStorage for dark mode setting on page load
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                document.body.classList.add('dark-menu');
                $('.Global-Loader').addClass('darkmode');
            }
        });
    </script>
     @if(Session::has('toastr'))
                        <script>
                            toastr.error(
                                '{{ Session::get('toastr.message') }}',
                                '{{ Session::get('toastr.title') }}',
                            );
                        </script>
                    @endif
    @yield('js')
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
</body>

</html>
