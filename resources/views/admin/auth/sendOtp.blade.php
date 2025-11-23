<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h4>{{ trans('OTP Verification') }}</h4>
                </div>
                <div class="card-body">
                    <p>{{ trans('Please enter the OTP sent to your email.') }}</p>
                    <div class="mb-3">
                        <strong>{{ trans('OTP:') }}</strong> {{ $otp }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ trans('Expires at:') }}</strong> {{ $otp_expire_at }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
