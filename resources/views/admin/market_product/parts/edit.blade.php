<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('image') }}</label>
                    <input type="file" class="dropify" name="image" id="image" @isset($obj->image)data-default-file="{{get_file($obj->image)}}"@endisset value="{{  $obj->image ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in english') }}</label>
                    <input type="text" class="form-control" name="title[en]" id="title" value="{{$obj->translate('title','en')}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title in arabic') }}</label>
                    <input type="text" class="form-control" name="title[ar]" id="title" value="{{$obj->translate('title','ar')}}">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('description in english') }}</label>
                    <input type="text" class="form-control" name="description[en]" id="description" value="{{$obj->translate('description','en')}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('description in arabic') }}</label>
                    <input type="text" class="form-control" name="description[ar]" id="description" value="{{$obj->translate('description','ar')}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="location" class="form-control-label">{{ trns('location') }}</label>
                    <input type="text" class="form-control" name="location" id="location" value="{{$obj->location}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="stock" class="form-control-label">{{ trns('stock') }}</label>
                    <input type="number" class="form-control" name="stock" id="stock" value="{{$obj->stock}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="price" class="form-control-label">{{ trns('price') }}</label>
                    <input type="number" step="0.01" class="form-control" name="price" id="price" value="{{$obj->price}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="market_product_category_id" class="form-control-label">{{ trns('market_product_category') }}</label>
                    <select class="form-control" name="market_product_category_id" id="market_product_category_id">
                        @foreach($marketProductCategories as $marketProductCategory)
                            <option @if($marketProductCategory->id==$obj->market_product_category_id) @endif value="{{$marketProductCategory->id}}">{{ $marketProductCategory->title }}</option>
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
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();
    $('select').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });</script>
