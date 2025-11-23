<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="title[en]" class="form-control-label">{{ trns('title_in_english') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title_en">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title[ar]" class="form-control-label">{{ trns('title_in_arabic') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title_ar">
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
