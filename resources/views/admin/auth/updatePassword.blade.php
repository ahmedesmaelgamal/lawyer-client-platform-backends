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
                            {{ trns('Update Password') }}
                        </h4>

                        {{-- Error Message --}}
                        @if (session('error'))
                            <div class="alert alert-danger text-center">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Form --}}
                        <form action="{{ route('updateAdminPass') }}" id="UpdatePasswordForm" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="hidden" name="email" value="{{ $email }}">
                                <label class="form-label fw-bold">{{ trans('New_Password') }}</label>
                                <input type="password" class="form-control " name="New_Password">
                                @error('New_Password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">{{ trans('password_confirmation') }}</label>
                                <input type="password" class="form-control" name="New_Password_confirmation">
                                @error('New_Password_confirmation')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" id="verifyUpdatePasswordBtn"
                                    class="btn btn-primary px-4 rounded-3">
                                    {{ trans('verify') }}
                                </button>
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
        $('#verifyUpdatePasswordBtn').on('click', function(e) {
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            $('#UpdatePasswordForm').submit();
        });
    </script>
</body>

</html>
