<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('Forget Password') }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">

                        {{-- Logo --}}
                        <div class="text-center mb-4">
                            <img src="{{ getFile($setting->where('key', 'logo')->first()->value) }}" alt="logo"
                                style="width: auto; height: 100px; margin-bottom: 20px;">
                        </div>

                        {{-- Title --}}
                        <h4 class="text-center fw-bold mb-4">
                            {{ trns('Enter your Otp') }}
                        </h4>

                        {{-- Error Message --}}
                        @if (session('error'))
                            <div class="alert alert-danger text-center">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Form --}}
                        <form action="{{ route('admin.verifyOtp') }}" id="otpForm" method="POST">
                            @method('POST')
                            @csrf
                            <div class="form-group mb-3">
                                <input type="hidden" name="email" value="{{ old('email', $email) }}">
                                <label class="form-label fw-bold">{{ trans('otp') }}</label>
                                <input type="text" class="form-control @error('otp') is-invalid @enderror"
                                    name="otp" value="{{ old('otp') }}">
                                @isset($error)
                                    <span class="text-danger small">{{ $error }}</span>
                                @endisset
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" id="verifyOtpBtn" class="btn btn-primary px-4 rounded-3">
                                    {{ trans('verify') }}
                                </button>
                                <a href="{{ url()->previous() }}" id="loginBtn"
                                    class="btn btn-outline-secondary px-4 rounded-3">
                                    <!-- #region --> {{ trans('return') }}
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#verifyOtpBtn').on('click', function(e) {
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            $('#otpForm').submit();
        });
    </script>
</body>

</html>
