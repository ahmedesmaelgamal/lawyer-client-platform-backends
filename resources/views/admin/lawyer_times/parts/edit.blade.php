<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="day" class="form-control-label">{{ trns('day') }}</label>
                    <select class="form-control" name="day" id="day">
                        @foreach($weekDays as $weekDay)
                            <option value="{{ $weekDay }}"
                                    @if($weekDay==$obj->$weekDay) selected @endif>{{ $weekDay->lang() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="from" class="form-control-label">{{ trns('from') }}</label>
                    <input type="time" class="form-control" name="from" id="from" value="{{$obj->from}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="to" class="form-control-label">{{ trns('to') }}</label>
                    <input type="time" class="form-control" name="to" id="to" value="{{$obj->to}}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('status') }}</label>
                    <select class="form-control" name="status" id="status">
                        @foreach($lawyerStatuses as $status)
                            <option value="{{ $status->value }}"
                                    @if($status->value==$obj->status) selected @endif>{{ $weekDay->lang() }}
                                > {{ $status->lang() }}</option>
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
