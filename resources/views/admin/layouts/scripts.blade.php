<!-- DATATABLE CSS -->
<link href="{{ asset('assets/admin') }}/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('assets/admin') }}/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css"
    rel="stylesheet" />

<!-- JQUERY JS -->
<script src="{{ asset('assets/admin') }}/assets/js/jquery-3.4.1.min.js"></script>

<!-- DATATABLE JS -->
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/datatable.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/dataTables.buttons.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/jszip.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/pdfmake.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/vfs_fonts.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/buttons.html5.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/buttons.print.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/datatable/fileexport/buttons.colVis.min.js"></script>

<!-- Bootstrap JS (with Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- BOOTSTRAP JS -->
{{-- <script src="{{asset('assets/admin')}}/assets/plugins/bootstrap5/js/bootstrap.bundle.min.js"></script> --}}
{{-- <script src="{{asset('assets/admin')}}/assets/plugins/bootstrap5/js/popper.min.js"></script> --}}

<!-- SPARKLINE JS-->
<script src="{{ asset('assets/admin') }}/assets/js/jquery.sparkline.min.js"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{ asset('assets/admin') }}/assets/js/circle-progress.min.js"></script>

<!-- RATING STARJS -->
<script src="{{ asset('assets/admin') }}/assets/plugins/rating/jquery.rating-stars.js"></script>

<!-- EVA-ICONS JS -->
<script src="{{ asset('assets/admin') }}/assets/iconfonts/eva.min.js"></script>

<!-- INPUT MASK JS-->
<script src="{{ asset('assets/admin') }}/assets/plugins/input-mask/jquery.mask.min.js"></script>

<!-- SIDE-MENU JS-->
<script src="{{ asset('assets/admin') }}/assets/plugins/sidemenu/sidemenu.js"></script>

{{-- <!-- PERFECT SCROLL BAR js--> --}}
<script src="{{ asset('assets/admin') }}/assets/plugins/p-scroll/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets/admin') }}/assets/plugins/sidemenu/sidemenu-scroll-rtl.js"></script>

<!-- CUSTOM SCROLLBAR JS-->
<script src="{{ asset('assets/admin') }}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- SIDEBAR JS -->
<script src="{{ asset('assets/admin') }}/assets/plugins/sidebar/sidebar-rtl.js"></script>

<!-- CUSTOM JS -->
<script src="{{ asset('assets/admin') }}/assets/js/custom.js"></script>

<!-- Switcher JS -->
<script src="{{ asset('assets/admin') }}/assets/switcher/js/switcher-rtl.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script> --}}
<script src="{{ asset('assets/dropify/js/dropify.min.js') }}"></script>

<script src="{{ asset('assets/admin/assets/js/select2.js') }}"></script>

<script src="{{ asset('assets/website/js/all.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.4/dist/sweetalert2.all.min.js"></script>

<script src="{{ asset('assets/fileUpload/fileUpload.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/uploadjs/image-uploader.min.js') }}"></script>

{{-- toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });
</script>

<script>
    window.addEventListener('online', () => {
        // window.location.reload();
        toastr.success("{{ trns('Internet connection has been restored.') }}");
    });
    window.addEventListener('offline', () => {
        toastr.error("{{ trns('Disconnected, please check your internet quality') }}");
    });
</script>

@yield('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script>
    $(document).on('click', '.image-popup', function(e) {
        e.preventDefault(); // Prevent the default action
        $(this).magnificPopup({
            items: {
                src: $(this).attr('href') // Ensure this pulls the correct URL
            },
            type: 'image',
            closeOnContentClick: true,
            image: {
                verticalFit: true
            }
        }).magnificPopup('open');
    });
</script>
<script>
    // code for control input arabic and english
    $(document).on('input', 'input[name*="ar"], textarea[name*="ar"]', function() {
        if (/[A-Za-z]/.test(this.value)) {
            this.value = '';
            toastr.error("{{ trns('please_enter_arabic_only') }}");
        }
    });

    $(document).on('input', 'input[name*="en"], textarea[name*="en"]', function() {
        if (/[\u0600-\u06FF]/.test(this.value)) {
            this.value = '';
            toastr.error("{{ trns('please_enter_english_only') }}");
        }
    });
</script>
<script type="text/javascript" src="{{ asset('richtexteditor') }}/rte.js"></script>
<script type="text/javascript" src='{{ asset('richtexteditor') }}/plugins/all_plugins.js'></script>
