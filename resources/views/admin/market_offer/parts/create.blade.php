<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image"
                        data-default-file="{{ getFile(null) }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="market_product_id" class="form-control-label">{{ trns('market product') }}</label>
                    <select class="form-control" name="market_product_id" id="market_product_id">
                        @foreach ($marketProducts as $marketProduct)
                            <option value="{{ $marketProduct->id }}">{{ $marketProduct->title }}</option>
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
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="from" class="form-control-label">{{ trns('from') }}</label>
                    <input type="date" class="form-control" name="from" id="from"
                        min="{{ now()->toDateString() }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="to" class="form-control-label">{{ trns('to') }}</label>
                    <input type="date" class="form-control" name="to" id="to"
                        min="{{ now()->toDateString() }}">
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
