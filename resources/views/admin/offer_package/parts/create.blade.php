<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in arabic') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in english') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="number_of_ads" class="form-control-label">{{ trns('number_of_ads') }}</label>
                    <input type="number" class="form-control" name="number_of_ads" id="number_of_ads">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="price" class="form-control-label">{{ trns('price') }}</label>
                    <input type="number" step="0.01" class="form-control" name="price" id="price">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="discount" class="form-control-label">{{ trns('discount') }}</label>
                    <input type="number" class="form-control" name="discount" id="discount">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <select class="form-control" name="status" id="status">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->lang() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="number_of_days" class="form-control-label">{{ trns('number_of_days') }}</label>
                    <input type="number" class="form-control" name="number_of_days" id="number_of_days">
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
