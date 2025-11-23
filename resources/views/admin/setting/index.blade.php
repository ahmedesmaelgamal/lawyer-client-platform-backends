@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | {{ $bladeName }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"> {{ $bladeName }} {{ config()->get('app.name') }}</h3>
        </div>
        <div class="card-body">
            <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 border-right">
                        <div class="p-3 py-5 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">{{ trns('settings') }}</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ getFile($settings->where('key', 'logo')->first()->value) }}"
                                         class="rounded-circle " width="150" height="150">
                                </div>

                                <div class="col-md-4">
                                    <img src="{{ getFile($settings->where('key', 'fav_icon')->first()->value) }}"
                                         class="rounded-circle mt-1" width="150" height="150">
                                </div>
                                <div class="col-md-4">
                                    <img src="{{ getFile($settings->where('key', 'loader')->first()->value) }}"
                                         class="rounded-circle mt-1" width="150" height="150">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <label class="labels">{{ trns('logo') }}</label>
                                    <input type="file" class="form-control" name="logo">
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="labels">{{ trns('fav_icon') }}</label>
                                    <input type="file" class="form-control" name="fav_icon">
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="labels">{{ trns('loader') }}</label>
                                    <input type="file" class="form-control" name="loader">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <label class="labels">{{ trns('app_version_android') }}</label>
                                    <input type="text" class="form-control" name="app_version_android"
                                           value="{{ optional($settings->where('key', 'app_version_android')->first())->value }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="labels">{{ trns('app_version_ios') }}</label>
                                    <input type="text" class="form-control" name="app_version_ios"
                                           value="{{ optional($settings->where('key', 'app_version_ios')->first())->value }}">
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="filePrice" class="form-control-label">{{ trns('filePrice') }}</label>
                                        <input type="text" class="form-control"
                                                value="{{ optional($settings->where('key', 'filePrice')->first())->value }}"
                                                name="filePrice" id="filePrice">
                                    </div>
                                </div>
                                <div class=" col-md-4 mt-3">
                                    <label class="labels">{{ trns('app_mentainance') }}</label>
                                    <select class="form-control" name="app_mentainance">
                                        <option value="true"
                                            {{ $settings->where('key', 'app_mentainance')->first()->value == 'true' ? 'selected' : '' }}>
                                            {{ trns('yes') }}</option>
                                        <option value="false"
                                            {{ $settings->where('key', 'app_mentainance')->first()->value == 'false' ? 'selected' : '' }}>
                                            {{ trns('no') }}</option>
                                    </select>
                                </div>

                                <div class=" col-md-4 mt-3">
                                    <label class="labels">{{ trns('system_language') }}</label>
                                    <select class="form-control" name="system_language">
                                        <option value="ar" {{ lang() == 'ar' ? 'selected' : '' }}>العربية</option>
                                        <option value="en" {{ lang() == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="labels">{{ trns('about_in_english') }}</label>
                                <textarea class="form-control" name="body[en]" id="body[en]">
                                {{ $settings->where('key', 'about_en')->first()->value }}
                                </textarea>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="labels">{{ trns('about_in_arabic') }}</label>
                                <textarea class="form-control" name="body[ar]" id="body[ar]">
                                {{ $settings->where('key', 'about_ar')->first()->value }}
                                </textarea>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="labels">{{ trns('court_case_vat') }}</label>
                                <input type="text" class="form-control" name="court_case_vat"
                                       value="{{ $settings->where('key', 'court_case_vat')->first()->value }}">
                            </div>
                            <div class="col-md-12 mt-3"><label
                                    class="labels">{{ trns('referral_sender_points') }}</label><input type="text"
                                                                                                      class="form-control"
                                                                                                      name="referral_sender_points"
                                                                                                      value="{{ $settings->where('key', 'referral_sender_points')->first()->value }}">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="labels">{{ trns('referral_receiver_points') }}</label>
                                <input type="text"
                                       class="form-control"
                                       name="referral_receiver_points"
                                       value="{{ $settings->where('key', 'referral_receiver_points')->first()->value }}">
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="mt-5 text-right mr-5">
                                <button type="submit" class="btn btn-primary"
                                        id="updateButton">{{ trns('update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- End Form -->
    @include('admin.layouts.myAjaxHelper')

    @section('ajaxCalls')
        <script>
            editScript();
        </script>
    @endsection
@endsection
