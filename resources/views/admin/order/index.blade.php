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
                        {{-- <button class="btn btn-danger btn-icon text-white" id="bulk-delete">
                            <span><i class="fe fe-trash"></i></span> {{ trns('delete selected') }}
                        </button> --}}

                        {{-- <button class="btn btn-secondary btn-icon text-white" id="bulk-update">
                            <span><i class="fe fe-trending-up"></i></span> {{ trns('update selected') }}
                        </button> --}}


                        <button class="btn btn-dark btn-icon text-white" onclick="cleanSearch()">
                            <span>
                                <i class="fe fe-trash"></i>
                            </span>
                        </button>
                        <button class="btn btn-blue btn-icon text-white" data-bs-toggle="modal"
                            data-bs-target="#search_modal">
                            <span>
                                <i class="fe fe-search"></i>
                            </span>
                        </button>
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
                                <th class="min-w-25px">{{trns('lawyer') }}</th>
                                <th class="min-w-25px">{{ trns('market_product') }}</th>
                                <th class="min-w-25px">{{ trns('qty') }}</th>
                                <th class="min-w-25px">{{ trns('phone') }}</th>
                                <th class="min-w-25px">{{ trns('address') }}</th>
                                <th class="min-w-25px">{{ trns('total_price') }}</th>
                                <th class="min-w-25px">{{ trns('status') }}</th>

                                {{-- <th class="min-w-50px rounded-end">{{ trns('actions') }}</th> --}}
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
                        <p>{{  trns('are_you_sure_you_want_to_delete_this_obj')}} <span id="title"
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

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">{{  trns('object_details')}}</h5>
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


<!--search MODAL -->
<div class="modal fade" id="search_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trns('search') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="filterForm" method="get">
                    <div class="row">


                        {{-- @dd($lawyers) --}}
                        <!-- lawyer -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ trns('lawyer') }}</label>
                                <select class="form-control select2" id="search_lawyer_id" name="search_lawyer_id">
                                    <option value="" selected disabled>select lawyer</option>
                                    @foreach ($lawyers as $index => $lawyer)
                                        <option class="form-control" value="{{ $index }}">{{ $lawyer }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ trns('status') }}</label>
                                <select class="form-control select2" name="search_status" id="search_status">
                                    <option value="" selected disabled>select status</option>
                                    @foreach ($statuses as $status)
                                        <option class="form-control" value="{{ $status->value }}">{{ $status->lang() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>





                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="cleanSearch()" data-dismiss="modal">مسح البحث</button>
                        <button type="button" id="filterButton" data-dismiss="modal" class="btn btn-primary">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- MODAL CLOSED -->


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
                        <p>{{ trns("are_you_sure_you_want_to_delete_selected_items") }}</p>
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
                        <p>{{ trns("are_you_sure_you_want_to_update_selected_items") }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ trns('cancel') }}</button>
                        <button type="button" class="btn btn-send" id="confirm-update-btn">{{ trns('update') }}</button>
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
        var columns = [
            {
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                }
            },



            {data: 'id', name: 'id'},
            {data: 'lawyer_id', name: 'lawyer_id'},
            {data: 'market_product_id', name: 'market_product_id'},
            {data: 'qty', name: 'qty'},
            {data: 'phone', name: 'phone'},
            {data: 'address', name: 'address'},
            {data: 'total_price', name: 'total_price'},
            {data: 'status', name: 'status'},
            // {data: 'action', name: 'action', orderable: false, searchable: false},
        ]


        function cleanSearch() {
            $('#search_status').val(null).trigger('change');
            $('#search_lawyer_id').val(null).trigger('change');

            var table = $('#dataTable').DataTable();
            table.ajax.reload(null, false);
        }
        $('.select2').select2({});

        $(document).ready(function() {
            $('.select2').select2({});

            $(document).on('click', '#filterButton', function() {
                const table = $('#dataTable').DataTable();
                table.ajax.reload(); // Reload the DataTable with new filters
            });
        });

        const ajax = {
            url: "{{ route($route . '.index') }}",
            data: function(d) {
                d.search_lawyer_id = $('select[name="search_lawyer_id"]').val();
                d.search_status = $('select[name="search_status"]').val();
            }
        };

        showData(ajax, columns);




        // showData('{{route($route.'.index')}}', columns);

        // Delete Using Ajax
        deleteScript('{{route($route.'.destroy',':id')}}');
        deleteSelected('{{route($route.'.deleteSelected')}}');

        updateColumnSelected('{{route($route.'.updateColumnSelected')}}');


        // Add Using Ajax
        showAddModal('{{route($route.'.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route($route.'.edit',':id')}}');
        editScript();
    </script>

    <script>
        // for status
        $(document).on('click', '.statusBtn', function() {
            let ids = [];
            $('.statusBtn').each(function () {
                ids.push($(this).data('id'));
            });


            var val = $(this).is(':checked') ? 1 : 0;



            $.ajax({
                type: 'POST',
                url: '{{ route($route.'.updateColumnSelected')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ids': ids,
                },
                success: function(data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('Success', "{{ trns('active') }}");
                        } else {
                            toastr.warning('Success', "{{ trns('inactive') }}");}
                    } else {
                        toastr.error('Error', "{{trns('something_went_wrong')}}");
                    }
                },
                error: function() {
                    toastr.error('Error', "{{trns('something_went_wrong')}}");
                }
            });
        });
    </script>


@endsection


