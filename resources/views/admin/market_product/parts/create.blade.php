<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in english') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in arabic') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title">
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="description[en]" class="form-control-label">{{ trns('description in english') }}</label>
{{--                    <input type="text" class="form-control" name="description[en]" id="description">--}}
                    <textarea class="form-control" name="description[en]" id="description[en]"></textarea>

                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="description[ar]" class="form-control-label">{{ trns('description in arabic') }}</label>
                    <input type="text" class="form-control" name="description[ar]" id="description[ar]">
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="location" class="form-control-label">{{ trns('location') }}</label>
                    <input type="text" class="form-control" name="location" id="location">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="stock" class="form-control-label">{{ trns('stock') }}</label>
                    <input type="number" class="form-control" name="stock" id="stock">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="price" class="form-control-label">{{ trns('price') }}</label>
                    <input type="number" step="0.01" class="form-control" name="price" id="price">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="market_product_category_id"
                           class="form-control-label">{{ trns('market_product_category') }}</label>
                    <select class="form-control" name="market_product_category_id" id="market_product_category_id">
                        @foreach($marketProductCategories as $marketProductCategory)
                            <option value="{{$marketProductCategory->id}}">{{ $marketProductCategory->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
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
