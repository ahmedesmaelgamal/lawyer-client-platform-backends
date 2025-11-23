<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">{{ trns('email') }}</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{ trns('password') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="national_id" class="form-control-label">{{ trns('national_id') }}</label>
                    <input type="text" class="form-control" name="national_id" id="national_id">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">{{ trns('phone') }}</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="points" class="form-control-label">{{ trns('points') }}</label>
                    <input type="number" class="form-control" name="points" id="points" value="0">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="city_id" class="form-control-label">{{ trns('city') }}</label>
                    <select class="form-control" name="city_id" id="city_id">
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->title }}</option>
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
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
