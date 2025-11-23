@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ $bladeName }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')
<style>
    .cursor-pointer{
        cursor: pointer;
    }
</style>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ $bladeName }} {{trns(env('APP_NAME_EN')) }}</h3>
                    <div class="">
                     
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">{{ trns('#') }}</th>
                                <th class="min-w-25px">{{ trns('lawyer_name') }}</th>
                                <th class="min-w-25px">{{ trns('client_name') }}</th>
                                <th class="min-w-10px">{{ trns('status') }}</th>
                                <th class="min-w-50px">{{ trns('Report') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Body Modal -->
        <div class="modal fade" id="fullBodyModal" tabindex="-1" aria-labelledby="fullBodyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Full Text</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- full body will appear here -->
            </div>
            </div>
        </div>
        </div>

    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>


        var columns = [
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'lawyer_id',
                name: 'lawyer_id'
            },
            {
                data: 'client_id',
                name: 'client_id'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'body',
                name: 'body'
            }
        ]

        showData('{{route($route.'.index')}}', columns);


        updateStatus('{{ route($route . '.updateColumnSelected') }}');
    </script>
    <script>
        // for status
        $(document).on('click', '.statusBtn', function () {
            let ids = [];
            $('.statusBtn').each(function () {
                ids.push($(this).data('id'));
            });


            var val = $(this).is(':checked') ? 1 : 0;


            $.ajax({
                type: 'POST',
                url: '{{ route($route . '.updateColumnSelected') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function (data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('Success', "{{ trns('active') }}");
                        } else {
                            toastr.warning('Success', "{{ trns('inactive') }}");
                        }
                    } else {
                        toastr.error('Error', "{{ trns('something_went_wrong') }}");
                    }
                },
                error: function () {
                    toastr.error('Error', "{{ trns('something_went_wrong') }}");
                }
            });
        });
    </script>

    <script>
        function showFullBody(id) {
            const element = document.getElementById('report-' + id);
            const body = element.getAttribute('data-body');
            document.getElementById('modalBodyContent').innerText = body;
            let modal = new bootstrap.Modal(document.getElementById('fullBodyModal'));
            modal.show();
        }
    </script>
@endsection
