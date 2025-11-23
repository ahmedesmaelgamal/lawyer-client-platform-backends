<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="community_sub_category_id"
                           class="form-control-label">{{ trns('community_sub_category') }}</label>
                    <select class="form-control" name="community_sub_category_id" id="community_sub_category_id">
                        @foreach($communitySubCategories as $communitySubCategory)
                            <option
                                value="{{$communitySubCategory->id}}">{{ $communitySubCategory->getTranslation('title',app()->getLocale()) }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <select class="form-control" name="status" id="status">
                        @foreach($statuses as $status)
                            <option value="{{$status->value}}">{{ $status->lang() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="body[ar]" class="form-control-label">{{ trns('body in arabic') }}</label>
                    <textarea class="form-control editor" name="body[ar]" id="body1"></textarea>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="body[en]" class="form-control-label">{{ trns('body in english') }}</label>
                    <textarea class="form-control" name="body[en]" id="body2"></textarea>
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
<script>
    new RichTextEditor("#body1");
    new RichTextEditor("#body2");
</script>
