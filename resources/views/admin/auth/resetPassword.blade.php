<!doctype html>
<html lang="en">

<head>
    <title>{{ trns('login') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/auth') }}/css/style.css">
</head>

<body style="height: 100vh;">
    <section class="ftco-section"
        style="display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-7">
                    <div class="wrap" style="border-radius: 45px">
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100 text-center">
                                    <img src="{{ getFile($setting->where('key', 'logo')->first()->value) }}"
                                        alt="logo" style="width: auto; height: 100px; margin-bottom: 20px;">
                                    <h2 class="mb-4" style="font-weight: bolder;margin-bottom: 50px;">
                                        {{ trns('Enter your email address to receive a message with a verification code to reset your password.') }}
                                    </h2>
                                    <div class="d-flex justify-content-center">
                                        @if (session('error'))
                                            <large class="text-danger fw-bold">{{ session('error') }}</large>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.forgetPassword') }}" id="resetForm" method="post">
                                @csrf
                                <div class="form-group mt-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required>
                                    <label class="form-control-placeholder">{{ trans('email') }}</label>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mt-5 d-flex">
                                    <button type="submit" id="resetBtn"
                                        class="form-control btn btn-primary rounded submit px-3 m-2">
                                        {{ trns('next') }}
                                    </button>
                                    <a href="{{ url()->previous() }}" id="loginBtn"
                                        class="form-control btn btn-primary rounded submit px-3 m-2">
                                        {{ trns('return') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/auth') }}/js/jquery.min.js"></script>
    <script src="{{ asset('assets/auth') }}/js/popper.js"></script>
    <script src="{{ asset('assets/auth') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/auth') }}/js/main.js"></script>

    <script>
        $('#resetBtn').on('click', function(e) {
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            $('#resetForm').submit();
        });
    </script>
</body>

</html>
