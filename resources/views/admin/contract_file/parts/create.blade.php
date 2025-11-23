<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <input type="hidden" value="{{$contractCategory->id}}" name="contract_category_id">
                <div class="form-group">
                    <label for="file_path" class="form-control-label">{{ trns('file_path') }}</label>
                    <input type="file" class="dropify" 
                    accept="application/pdf,image/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
                    name="file_path" id="file_path">

                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="file_name[ar]" class="form-control-label">{{ trns('file_name_in_arabic') }}</label>
                        <input class="form-control" name="file_name[ar]" id="file_name[ar]">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="file_name[en]" class="form-control-label">{{ trns('file_name_in_english') }}</label>
                        <input class="form-control" name="file_name[en]" id="file_name[en]">
                    </div>
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
