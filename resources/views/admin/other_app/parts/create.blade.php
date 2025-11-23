<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name') }}</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns(key: 'android_url') }}</label>
                    <input type="text" class="form-control" name="android_url" id="android_url">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('ios_url') }}</label>
                    <input type="text" class="form-control" name="ios_url" id="ios_url">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('icon') }}</label>
                    <input type="file" class="dropify" name="icon" id="icon">
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

    });
</script>
