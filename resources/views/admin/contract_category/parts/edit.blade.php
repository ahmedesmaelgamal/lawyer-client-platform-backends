<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="title[en]" class="form-control-label">{{ trns('title_in_english') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title_en" value="{{$obj->translate('title','en')}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title[ar]" class="form-control-label">{{ trns('title_in_arabic') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title_ar" value="{{$obj->translate('title','ar')}}">
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
