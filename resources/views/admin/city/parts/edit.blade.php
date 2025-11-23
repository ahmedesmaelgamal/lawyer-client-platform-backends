<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in arabic') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title"
                           value="{{ $obj->translate('title','ar') }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in english') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title"
                           value="{{ $obj->translate('title','en') }}">
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="country_id" class="form-control-label">{{ trns('country_id') }}</label>
                    <select class="form-control" name="country_id" id="country_id">
                        @foreach($countries as $country)
                            <option @if($obj->country->id == $country->id) selected
                                    @endif value="{{ $country->id }}">{{ $country->title }}</option>
                        @endforeach
                    </select>
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
