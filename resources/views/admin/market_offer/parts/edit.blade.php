<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image" data-default-file="{{  getFile($obj->image) }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="market_product_id" class="form-control-label">{{ trns('market product') }}</label>
                    <select class="form-control" name="market_product_id" id="market_product_id">
                        @foreach($marketProducts as $marketProduct)
                            <option
                                @if($obj->market_product_id == $marketProduct->id) selected @endif
                             value="{{ $marketProduct->id }}">{{ $marketProduct->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <select class="form-control" name="status" id="status">
                        @foreach($statuses as $status)
                            <option
                                @if($obj->status == $status->value) selected @endif
                             value="{{ $status->value }}">{{ $status->lang() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="from" class="form-control-label">{{ trns('from') }}</label>
                    <input type="date" class="form-control" value="{{ $obj->from }}" name="from" id="from"
                     min="{{ now()->toDateString() }}"
                    >
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="to" class="form-control-label">{{ trns('to') }}</label>
                    <input type="date" class="form-control" value="{{ $obj->to }}" name="to" id="to"
                     min="{{ now()->toDateString() }}"
                    >
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
