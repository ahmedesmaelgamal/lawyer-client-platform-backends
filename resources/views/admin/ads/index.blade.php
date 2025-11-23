@php use App\Enums\AdConfirmationEnum; @endphp
@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | {{ $bladeName }}
@endsection
@section('page_name')
    {{ $bladeName }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ $bladeName }} {{ config()->get('app.name') }}</h3>
                    <div class="">
                        {{-- <button class="btn btn-secondary btn-icon text-white addBtn">
									<span>
										<i class="fe fe-plus"></i>
									</span> {{ trns('add_new') . ' ' . $bladeName }}
                        </button> --}}
                        @can('delete_ad_management')
                            <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                                <span><i class="fe fe-trash"></i></span> {{ trns('delete selected') }}
                            </button>
                        @endcan

                        {{-- <button class="btn btn-secondary btn-icon text-white" id="bulk-update">
                            <span><i class="fe fe-trending-up"></i></span> {{ trns('update selected') }}
                        </button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-25px">{{ trns('lawyer') }}</th>
                                    <th class="min-w-25px">{{ trns('package') }}</th>
                                    <th class="min-w-25px">{{ trns('status') }}</th>
                                    <th class="min-w-25px">{{ trns('ad_confirmation') }}</th>
                                    <th class="min-w-25px">{{ trns('from-date') }}</th>
                                    <th class="min-w-25px">{{ trns('to-date') }}</th>
                                    <th class="min-w-25px">{{ trns('image') }}</th>

                                    <th class="min-w-50px rounded-end">{{ trns('actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trns('delete') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>{{ trns('are_you_sure_you_want_to_delete_this_obj') }} <span id="title"
                                class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            {{ trns('close') }}
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">{{ trns('delete') }} !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


        <!--refuse reason modal -->
        <div class="modal fade" id="refuse_reason_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trns('refuse reason') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ $adConfirmation }}" method="post" id="confirmationForm">
                            @csrf
                            <input type="hidden" name="status" value="{{ \App\Enums\AdConfirmationEnum::REJECTED }}">

                            {{--                        <input id="delete_id" name="id" type="hidden"> --}}
                            {{--                        <label class="tgl-btn" dir="ltr" for="statusUser-' . $obj->id . '"></label> --}}

                            <textarea id="refuse_reason" class="form-control" name="refuse_reason" data-parsley-trigger="keyup"></textarea>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-bs-dismiss="modal"
                                    id="dismiss_refuse_reason_modal">
                                    {{ trns('close') }}
                                </button>

                                <button type="button" class="btn btn-danger refuse-reason-btn">
                                    {{ trns('confirm') }}
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- refuse reason closed -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">{{ trns('object_details') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->

        <!-- delete selected  Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">{{ trns('confirm_deletion') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ trns('are_you_sure_you_want_to_delete_selected_items') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ trns('cancel') }}</button>
                        <button type="button" class="btn btn-danger"
                            id="confirm-delete-btn">{{ trns('delete') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->


        <!-- update cols selected  Modal -->
        <div class="modal fade" id="updateConfirmModal" tabindex="-1" role="dialog"
            aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">{{ trns('confirm_change') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ trns('are_you_sure_you_want_to_update_selected_items') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ trns('cancel') }}</button>
                        <button type="button" class="btn btn-send"
                            id="confirm-update-btn">{{ trns('update') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete selected  Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                }
            },
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'lawyer_id',
                name: 'lawyer_id'
            },
            {
                data: 'package_id',
                name: 'package_id'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'ad_confirmation',
                name: 'ad_confirmation'
            },
            {
                data: 'from_date',
                name: 'from_date'
            },
            {
                data: 'to_date',
                name: 'to_date'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];

        let ajax = {
            url: '{{ route($route . '.index') }}',
            data: function(d) {
                d.status = "{{ request()->has('status') ? request()->status : null }}";
                d.ad_confirmation = "{{ request()->has('ad_confirmation') ? request()->ad_confirmation : null }}";
            }
        };
        showData(ajax, columns);

        // Delete Using Ajax
        deleteScript('{{ route($route . '.destroy', ':id') }}');
        deleteSelected('{{ route($route . '.deleteSelected') }}');

        updateColumnSelected('{{ route($route . '.updateColumnSelected') }}');


        // Add Using Ajax
        showAddModal('{{ route($route . '.create') }}');
        addScript();
        // Add Using Ajax
        showEditModal('{{ route($route . '.edit', ':id') }}');
        editScript();

        updateStatus('{{ route($route . '.updateColumnSelected') }}');
    </script>

    <script>
        // for status
        $(document).on('click', '.statusBtn', function() {
            let ids = [];
            $('.statusBtn').each(function() {
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
                success: function(data) {
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
                error: function() {
                    toastr.error('Error', "{{ trns('something_went_wrong') }}");
                }
            });


        });
        let acceptId = null
        $(document).on('click', '.accept-btn', function() {
            let status = $(this).data('status');
            let acceptId = $(this).data('accept-id');
            console.log(acceptId); // Correct ID for the clicked button
            $.ajax({
                type: 'POST',
                url: '{{ route($route . '.updateColumnSelectedForConfirmation') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': acceptId,
                    'status': status,
                    // 'column': 'ad_confirmation'
                },
                success: function(data) {
                    $('#dataTable').DataTable().ajax.reload();

                    if (data.status === 200) {
                        toastr.success('Success', "{{ trns('status_updated') }}");
                    } else {
                        toastr.error('Error', "{{ trns('something_went_wrong') }}");
                    }
                },
                error: function() {
                    toastr.error('Error', "{{ trns('something_went_wrong') }}");
                }
            });
        });
        // let acceptId = $('.accept_reason_btn').data('accept-id');
    </script>


    <script>
        $(document).on('click', '.refuse-btn', function() {
            // Store the data in the modal's button when the refuse button is clicked
            let status = $(this).data('status');
            let refuseId = $(this).data('refuse-id');

            // Update the modal's confirm button with these values
            $('.refuse-reason-btn')
                .data('status', status)
                .data('refuse-id', refuseId);
        });

        $(document).on('click', '.refuse-reason-btn', function() {
            let status = $(this).data('status');
            let refuseId = $(this).data('refuse-id');
            let refuseReason = $('');
            refuseReason = $('#refuse_reason').val();

            console.log('Sending:', {
                status,
                refuseId
            }); // Debug output

            $.ajax({
                type: 'POST',
                url: '{{ route($route . '.updateColumnSelectedForConfirmation') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': refuseId,
                    'status': status,
                    'refuse_reason': refuseReason,
                },
                success: function(data) {
                    $('#dataTable').DataTable().ajax.reload();
                    $('#refuse_reason_modal').modal('hide'); // Close the modal

                    if (data.status === 200) {
                        toastr.success('Success', "{{ trns('status_updated') }}");
                    } else {
                        toastr.error('Error', "{{ trns('something_went_wrong') }}");
                    }
                },
                error: function() {
                    toastr.error('Error', "{{ trns('something_went_wrong') }}");
                }
            });
        });
    </script>
@endsection
