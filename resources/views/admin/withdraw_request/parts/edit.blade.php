<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $updateRoute }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('user') }}</label>
                    <h4>{{ $obj->user->name }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('user_type') }}</label>
                    <h4>{{ \App\Enums\UserTypeEnum::from($obj->user_type)->lang() }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('withdraw_method') }}</label>
                    <h4>{{ trns($obj->payment_method) }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('way') }}</label>
                    <h4>{{ $obj->payment_key }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('amount') }}</label>
                    <h3 class="text-success">{{ number_format($obj->amount,2) }}</h3>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="image" class="form-control-label">{{ trns('created_at') }}</label>
                    <h4>{{ $obj->created_at->format('Y-m-d h:i a') }}</h4>
                </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <div class="btn w-100 approval-btn
                @if($obj->status == 'approved') btn-success @else btn-success-light @endif"
                             data-value="approved">
                            {{ trns('approved') }}
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <div class="btn w-100 approval-btn
                @if($obj->status == 'rejected') btn-danger @else btn-danger-light @endif"
                             data-value="rejected">
                            {{ trns('reject') }}
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="status" id="status" value="{{ $obj->status }}">

        </div>

        <div class="form-group" id="reject_reason_div" style="display: {{ $obj->status == 'rejected' ? 'block' : 'none' }};">
            <label for="reject_reason" class="form-control-label">{{ trns('reject_reason') }}</label>
            <textarea name="reject_reason" id="reject_reason" class="form-control" rows="5">{{ $obj->reject_reason }}</textarea>
        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify();

    $(document).ready(function() {
        // Initialize based on current status
        var currentStatus = $('#status').val();
        if (currentStatus === 'approved') {
            $('.approval-btn[data-value="approved"]').removeClass('btn-success-light').addClass('btn-success');
            $('#reject_reason_div').hide();
        } else if (currentStatus === 'rejected') {
            $('.approval-btn[data-value="rejected"]').removeClass('btn-danger-light').addClass('btn-danger');
            $('#reject_reason_div').show();
        }

        // Click handler
        $('.approval-btn').on('click', function() {
            // Reset all buttons to light state
            $('.approval-btn').removeClass('btn-success btn-danger')
                .addClass(function() {
                    return $(this).data('value') === 'approved'
                        ? 'btn-success-light'
                        : 'btn-danger-light';
                });

            // Activate clicked button
            if ($(this).data('value') === 'approved') {
                $(this).removeClass('btn-success-light').addClass('btn-success');
                $('#reject_reason_div').hide();
            } else {
                $(this).removeClass('btn-danger-light').addClass('btn-danger');
                $('#reject_reason_div').show();
            }

            // Update hidden input value
            $('#status').val($(this).data('value'));
        });
    });
</script>
