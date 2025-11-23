<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name in arabic') }}</label>
                    <input type="text" class="form-control" name="name[ar]" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name in english') }}</label>
                    <input type="text" class="form-control" name="name[en]" id="name">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('type') }}</label>
                    <select class="form-control select2" name="type" id="type">
                        <option value="1"> -- {{ trns('select_type')}} -- </option>
                        <option value="complete">{{ trns('finish_reasons') }}</option>
                        <option value="cancel">{{ trns('cancel_reasons') }}</option>
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
    $('.select2').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
