<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }} | {{ trns('login') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">
</head>

<body style="background-image: url('{{ asset('login-background.jpg') }}'); background-size: cover">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="wrap">
                        <div class="login-wrap p-4 p-md-5">
                            <div class="">
                                <div class="w-100">
                                    <h3 class="mb-4">{{ config('app.name') }} | {{ trns('admin_login') }}</h3>
                                    <div class="d-flex justify-content-center">
                                        <small id="errorMsg" class="text-danger"></small>
                                        <small id="successMsg" class="text-success ml-2"></small>
                                    </div>
                                </div>
                                {{-- 2FA FORM --}}
                        <form action="{{ route('admin.2fa.verify') }}"
                              id="twofaForm"
                              class="signin-form {{ session('2fa_required') ? '' : 'd-none' }}"
                              method="post" autocomplete="off">
                            @csrf
                            <input type="hidden" name="user_id" id="twofa_user_id" value="{{ session('2fa_user_id') ?? '' }}">

                            <div class="form-group mt-3 text-center">
                                <p>{{ trns('enter_2fa_code') }}</p>
                            </div>

                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="twofa_code" required placeholder="{{ trns('verification_code') }}">
                                <span class="error-text" data-for="twofa_code" style="display:none"></span>
                            </div>

                            <div class="form-group mt-5">
                                <button type="submit" id="twofaBtn" class="form-control btn btn-primary rounded submit px-3">
                                    {{ trns('verify_code') }}
                                </button>
                            </div>

                            <div class="mt-3 text-center">
                                <a href="#" id="backToLogin">{{ trns('back_to_login') ?? 'Back to login' }}</a>
                            </div>
                        </form>
                                <form action="{{ route('admin.login') }}" id="loginForm" class="signin-form"
                                    method="post">
                                    @csrf
                                    <div class="form-group mt-3">
                                        <input type="text" class="form-control" name="input" required>
                                        <label class="form-control-placeholder"
                                            for="username">{{ trns('Username') }}</label>
                                    </div>
                                    <div class="form-group">
                                        <input id="password-field" type="password" name="password" class="form-control"
                                            required>
                                        <label class="form-control-placeholder"
                                            for="password">{{ trns('Password') }}</label>
                                        <span toggle="#password-field"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="loginBtn"
                                            class="form-control btn btn-primary rounded submit px-3">{{ trns('login') }}
                                        </button>
                                    </div>
                                    <div class="form-group d-md-flex">
                                        <div class="w-50 text-left">
                                            <label
                                                class="checkbox-wrap checkbox-primary mb-0">{{ trns('Remember Me') }}
                                                <input type="checkbox" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!--                               password reset -->
                                    <div class="form-group text-center mt-3">
                                        <a style="text-decoration:underline"
                                            href="{{ route('admin.showForgetPassword') }}"
                                            class="btn btn-link text-primary ">
                                            {{ trns('Forgot_password?') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/popper.js') }}"></script>
    <script src="{{ asset('assets/auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/main.js') }}"></script>

    <script>
        (function($) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            function togglePassword() {
                $('.toggle-password').on('click', function() {
                    let input = $($(this).attr('toggle'));
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            }

            $(document).ready(function() {
                togglePassword();

                // LOGIN
                $('#loginBtn').on('click', function(e) {
                    e.preventDefault();
                    $('#loginForm').submit();
                });

                $('#loginForm').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = $('#loginBtn');
                    $('#errorMsg').text('');
                    $('#successMsg').text('');
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                    $.post(form.attr('action'), form.serialize())
                        .done(function(res) {
                            btn.prop('disabled', false).html(btn.data('label'));
                            if (res.status === 200) {
                                $('#successMsg').text(res.message);
                                window.location.href = res.redirect;
                            } else if (res.status === 202) {
                                $('#loginForm').addClass('d-none');
                                $('#twofaForm').removeClass('d-none');
                                $('#twofa_user_id').val(res.user_id);
                                $('#successMsg').text(res.message);
                            } else {
                                $('#errorMsg').text(res.message);
                            }
                        })
                        .fail(function(xhr) {
                            btn.prop('disabled', false).html(btn.data('label'));
                            $('#errorMsg').text(xhr.responseJSON?.message ||
                                "{{ trns('something_went_wrong') }}");
                        });
                });

                // 2FA
                $('#twofaForm').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = $('#twofaBtn');
                    $('#errorMsg').text('');
                    $('#successMsg').text('');
                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                    $.post(form.attr('action'), form.serialize())
                        .done(function(res) {
                            btn.prop('disabled', false).html(btn.data('label'));
                            if (res.status === 200) {
                                $('#successMsg').text(res.message);
                                window.location.href = res.redirect;
                            } else {
                                $('#errorMsg').text(res.message);
                            }
                        })
                        .fail(function(xhr) {
                            btn.prop('disabled', false).html(btn.data('label'));
                            $('#errorMsg').text(xhr.responseJSON?.message ||
                                "{{ trns('something_went_wrong') }}");
                        });
                });

                // BACK TO LOGIN
                $('#backToLogin').on('click', function(e) {
                    e.preventDefault();
                    $('#twofaForm').addClass('d-none');
                    $('#loginForm').removeClass('d-none');
                });
            });
        })(jQuery);
    </script>
</body>

</html>
