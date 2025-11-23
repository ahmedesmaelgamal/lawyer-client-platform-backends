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
                    <label for="currency" class="form-control-label">{{ trns('currency') }}</label>
                    <select class="form-control" name="currency" id="currency">
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>
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
