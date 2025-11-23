<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="Case_Specializations_id"
                            class="form-control-label">{{ trns('Case_Specialization') }}</label>
                        <select class="form-control" name="Case_Specializations_id" id="Case_Specializations_id">
                            <option value="">{{ trns('select Case_Specializations') }}</option>
                            @foreach ($caseSpecializations as $Case_Specialization)
                                <option value="{{ $Case_Specialization->id }}">{{ $Case_Specialization->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name in arabic') }}</label>
                    <input type="text" class="form-control" name="name[ar]" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('name in english') }}</label>
                    <input type="text" class="form-control" name="name[en]" id="name">
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
