<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute  }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">

            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="name" class="form-control-label">{{ trns('name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$obj->name}}">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="form-control-label">{{ trns('email') }}</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{$obj->email}}">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="password" class="form-control-label">{{ trns('password') }}</label>
                        <input type="password" class="form-control" name="password" id="password"
                               placeholder="********">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="national_id" class="form-control-label">{{ trns('national_id') }}</label>
                        <input type="text" class="form-control" name="national_id" id="national_id"
                               value="{{$obj->national_id}}">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="phone" class="form-control-label">{{ trns('phone') }}</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{$obj->phone}}">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="points" class="form-control-label">{{ trns('points') }}</label>
                        <input type="number" class="form-control" name="points" id="points" value="{{$obj->points}}">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="city_id" class="form-control-label">{{ trns('city') }}</label>
                        <select class="form-control" name="city_id" id="city_id">
                            @foreach($cities as $city)
                                <option
                                    value="{{ $city->id }}" {{ $obj->city_id == $city->id ? 'selected' : '' }}>{{ $city->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="country_id" class="form-control-label">{{ trns('country') }}</label>
                        <select class="form-control" name="country_id" id="country_id">
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="status" class="form-control-label">{{ trns('status') }}</label>
                        <select class="form-control" name="status" id="status">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}">{{ $status->lang() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="otp" class="form-control-label">{{ trns('otp') }}</label>
                        <input type="text" class="form-control" name="otp" id="otp" maxlength="6"
                               value="{{$obj->otp}}"/>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label for="otp_expire" class="form-control-label">{{ trns('otp_expire') }}</label>
                        <input type="datetime-local" class="form-control" name="otp_expire" id="otp_expire"
                               value="{{$obj->otp_expire}}">
                    </div>
                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
                <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
            </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
