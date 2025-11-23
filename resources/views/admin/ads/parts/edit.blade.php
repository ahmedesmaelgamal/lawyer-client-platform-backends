<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image" @isset($obj->image) data-default-file="{{get_file($obj->image)}}" @endisset value="{{  $obj->image ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="lawyer_id" class="form-control-label">{{ trns('lawyer') }}</label>
                    <select class="form-control" name="lawyer_id" id="lawyer_id">
                        @foreach($lawyers as $lawyer)
                            <option @if($lawyer->id==$obj->lawyer_id) selected @endif value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <select class="form-control" name="status" id="status">
                        @foreach($statuses as $status)
                            <option @if($status->value==$obj->status) selected @endif value="{{ $status->value }}">{{ $status->lang() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="from_date" class="form-control-label">{{ trns('from_date') }}</label>
                    <input type="date" class="form-control" name="from_date" id="from_date" value="{{$obj->from_date}}">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="to_date" class="form-control-label">{{ trns('to_date') }}</label>
                    <input type="date" class="form-control" name="to_date" id="to_date" value="{{$obj->to_date}}">
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
