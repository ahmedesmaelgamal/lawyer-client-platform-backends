<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }}</label>
                    <input type="text" class="form-control" name="title" id="title">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="client_id" class="form-control-label">{{ trns('client_id') }}</label>
                    <select class="form-control" name="client_id" id="client_id">
                        @foreach($clients as $client)
                            <option value="{{$client}}">{{ trans($client) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="case_estimated_price"
                           class="form-control-label">{{ trns('case_estimated_price') }}</label>
                    <input type="text" class="form-control" name="case_estimated_price" id="case_estimated_price">
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="details" class="form-control-label">{{ trns('details') }}</label>
                    <input type="text" class="form-control" name="details" id="details">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <input type="text" class="form-control" name="status" id="status">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="case_final_price" class="form-control-label">{{ trns('case_final_price') }}</label>
                    <input type="number" class="form-control" name="case_final_price" id="case_final_price">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="speciality_id" class="form-control-label">{{ trns('speciality_id') }}</label>
                    <select class="form-control" name="speciality_id" id="speciality_id">
                        @foreach($specialities as $speciality)
                            <option value="{{$speciality}}">{{ $speciality->lang() }}</option>
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

    });</script>
