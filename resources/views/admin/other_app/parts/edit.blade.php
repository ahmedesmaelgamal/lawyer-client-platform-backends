<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">

            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ $obj->name }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('android_url') }}</label>
                    <input type="url" class="form-control" name="android_url" id="android_url"
                        value="{{ $obj->android_url }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('ios_url') }}</label>
                    <input type="url" class="form-control" name="ios_url" id="ios_url"
                        value="{{ $obj->ios_url }}">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('icon') }}</label>
                    <input type="file" class="dropify" name="icon" id="icon"
                        data-default-file="{{ asset($obj->icon) }}">
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

    });
</script>
