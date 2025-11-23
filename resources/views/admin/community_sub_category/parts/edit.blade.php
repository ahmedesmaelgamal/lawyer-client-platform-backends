<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="community_category_id"
                           class="form-control-label">{{ trns('community_category_id') }}</label>
                    <select class="form-control" name="community_category_id" id="community_category_id">
                        @foreach($communityCategories as $community_category)
                            <option @if($community_category->id==$obj->community_category_id) selected @endif value="{{$community_category->id}}">{{ $community_category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }}</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{$obj->title}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <select class="form-control" name="status" id="status">
                        @foreach($statuses as $status)
                            <option  @if($status->value==$obj->status) selected @endif value="{{$status->value}}">{{ $status->lang() }}</option>
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
