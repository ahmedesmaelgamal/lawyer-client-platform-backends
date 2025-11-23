@extends('admin/layouts/master')

@section('title')
    {{ trns('security_settings') }}
@endsection

@section('page_name')
    {{ trns('security_settings') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trns('security_settings') }}</h3>
        </div>
        <div class="card-body">
            @if(auth()->user()->twofa_verify == 1)
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check-circle"></i> {{ trns('two_fa_verified') }}</h5>
                    <p>{{ trns('two_fa_already_enabled') }}</p>
                    <form method="POST" action="{{ route('secure_settings.disable') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger mt-3">
                            <i class="fas fa-lock-open mr-2"></i> {{ trns('disable_two_fa') }}
                        </button>
                    </form>
                </div>
            @else
                <form method="POST" enctype="multipart/form-data" action="{{ route('secure_settings.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ trns('two_factor_authentication') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ trns('qrcode_for_2fa') }}</label>
                                                <div class="text-center p-4 border rounded bg-light">
                                                    <div class="mb-3">
                                                        <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                                    </div>
                                                    <p class="text-muted small">
                                                        {{ trns('scan_qr_with_authenticator_app') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ trns('manual_setup_code') }}</label>
                                                @if(isset($manualCode))
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" value="{{ $manualCode }}" id="secretCode" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" type="button" onclick="copySecretCode()">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>{{ trns('verification_code') }}</label>
                                                <input type="text" class="form-control" name="twofa_code" placeholder="{{ trns('enter_verification_code') }}">
                                                @error('twofa_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary px-4" id="updateButton">
                                <i class="fas fa-save mr-2"></i>{{ trns('verify_and_enable') }}
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @include('admin.layouts.myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        function copySecretCode() {
            const secretCode = document.getElementById('secretCode');
            secretCode.select();
            document.execCommand('copy');
            toastr.success('{{ trns("secret_code_copied") }}');
        }

        // Initialize form validation
        $('form').validate({
            rules: {
                twofa_code: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                }
            },
            messages: {
                twofa_code: {
                    required: '{{ trns("verification_code_required") }}',
                    digits: '{{ trns("verification_code_must_be_numeric") }}',
                    minlength: '{{ trns("verification_code_must_be_6_digits") }}',
                    maxlength: '{{ trns("verification_code_must_be_6_digits") }}'
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    </script>
@endsection
