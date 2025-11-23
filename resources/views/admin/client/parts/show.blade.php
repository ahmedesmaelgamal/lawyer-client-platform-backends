@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ trns('employee_details') }}
@endsection

@section('page_name')
    {{ trns('employee_details') }}
@endsection
@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .printable,
            .printable * {
                visibility: visible;
            }

            .printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print-btn {
                visibility: hidden;
            }
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <a href="{{ route('lawyers.index') }}" class="btn btn-success text-white addBtn">
                                <span>
                                    <i class="fe fe-arrow-{{ flang('left', 'right') }}"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-6">
                            <div class="wideget-user text-center">
                                <div class="wideget-user-desc">
                                    <div class="wideget-user-img">
                                        {{--                                        <img class="image-popup" src="{{ getFile($obj->image) }}" alt="img">--}}
                                        <img alt="client image" href="{{  getFile($obj->image) }} "
                                             src="{{  getFile($obj->image) }} "
                                             class="image-popup avatar avatar-md rounded-circle" style="cursor:pointer;"
                                             width="100" height="100" loading="lazy">

                                    </div>
                                    {{-- @dd($obj->courtCases->last()->courtCaseUpdates) --}}
                                    <div class="user-wrap">
                                        <h4 class="mb-1 text-capitalize">{{ $obj->name }}</h4>
                                        <h4 class="mb-1 text-capitalize">
                                            {{ 'Last case update : ' . ($obj->courtCases->last()->courtCaseUpdates->count() ? $obj->courtCases->last()->courtCaseUpdates->last()->created_at->format('Y-m-d') : 'N/A') }}
                                        </h4>
                                        <h4>
                                            @if ($obj->status == 'active')
                                                <span class="badge badge-success">{{ trns('active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ trns('inactive') }}</span>
                                            @endif
                                        </h4>
                                        <h6 class="text-muted mb-4">
                                            {{-- @if ($obj->attendances->count() > 0)
                                                {{ trns('last_seen') }} :
                                                {{ Carbon\Carbon::parse($obj->attendances->first()->check_in)->diffForHumans() }}
                                            @endif --}}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <div>
                                {{-- <button type="button" data-id="{{ $obj->id }}" class="dropdown-item btn editBtn">
                                    <i class="ml-2 fa fa-edit text-primary"></i>
                                    {{ trns('edit') }}
                                </button>
                                <button type="button" data-id="{{ $obj->id }}"
                                        class="dropdown-item btn incentiveBtn">
                                    <i class="ml-2 fas fa-money-bill text-info"></i>
                                    {{ trns('incentive_/_deduction') }}
                                </button> --}}
                                {{-- <button
                                    onclick="window.location.href='{{ route('users.getIncentives', $obj->id) }}'"
                                    class="dropdown-item btn">
                                    <i class="fas fa-wave-square text-info"></i>
                                    {{ trns('oprations') }}
                                </button> --}}


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-1" class="tab-action active show"
                                                data-toggle="tab">{{ trns('Personal Information') }}</a>
                                </li>
                                <li class=""><a href="#tab-2" class="tab-action"
                                                data-toggle="tab">{{ trns('transactions') }}</a></li>
                                <li class=""><a href="#tab-3" class="tab-action"
                                                data-toggle="tab">{{ trns('notifications') }}</a></li>
                                <li class=""><a href="#tab-4" class="tab-action"
                                                data-toggle="tab">{{ trns('court cases') }}</a></li>
                                <li class=""><a href="#tab-5" class="tab-action"
                                                data-toggle="tab">{{ trns('post comments') }}</a></li>
                                <li class=""><a href="#tab-6" class="tab-action"
                                                data-toggle="tab">{{ trns('blog reactions') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane  active show" id="tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <div>
                                        <h5><strong>{{ trns('Personal Information') }}</strong></h5>
                                    </div>

                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>{{ trns('Name') }} :</strong>
                                                {{ $obj->name ?? 'N/A' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('phone') }} :</strong>
                                                {{ $obj->national_id ?? 'N/A' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('phone') }} :</strong> {{ $obj->phone ?? 'N/A' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('email') }} :</strong> {{ $obj->email ?? 'N/A' }}</td>
                                        </tr>
                                        </tbody>

                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('country') }} :</strong>
                                                {{ $obj->country->title ?? '' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('country') }} :</strong> {{ $obj->points ?? '' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('city') }} :</strong>
                                                {{ $obj->city->title ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        </tbody>


                                        {{-- <td class="text-capitalize">
                                            @if ($obj->status == 'active')
                                                <span class="badge badge-success">{{ trns('active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ trns('inactive') }}</span>
                                            @endif
                                        </td> --}}


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-2">
                    <div class="card">
                        <div class="card-body">
                            {{-- @dd($obj->walletTransactions) --}}
                            <div id="profile-log-switch">
                                <div class="table-responsive">
                                    @if ($obj->walletTransactions->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td> #</td>
                                                <td>{{ trns('credit') }}</td>
                                                <td class="text-center">
                                                    {{ trns('debit') }}
                                                </td>
                                                {{-- <td>{{ trns('court case') }}</td> --}}
                                                <td class="text-center">
                                                    {{ trns('date') }}
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach ($obj->walletTransactions as $wallet)
                                                <tr>
                                                    <td class="text-capitalize">
                                                        {{ $loop->iteration ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $wallet->credit ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $wallet->debit ?? 'N/A' }}
                                                    </td>
                                                    {{-- <td class="text-capitalize">
                                                        {{ $obj->courtCaseEvents->first()->courtCase->title ?? 'N/A' }}
                                                    </td> --}}
                                                    <td class="text-capitalize">
                                                        {{ Carbon\Carbon::parse($wallet->date)->format('d-m-Y') ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{--                                                {{ $obj->walletTransactions()->paginate(10)->appends(['tab' => request('tab')])->links() }}--}}

                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-3">
                    <div class="card">

                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- @dd($obj->notifications) --}}

                                <div class="table-responsive">
                                    @if ($obj->notifications->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('title') }}</td>
                                                <td>{{ trns('body') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->notifications as $notification)
                                                <tr>
                                                    <td class="text-capitalize">{{ $notification->title }}</td>
                                                    <td class="text-capitalize">{{ $notification->body }}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- @dd($obj->courtCases->count()) --}}
                                <div class="table-responsive">
                                    @if ($obj->courtCases->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('title') }}</td>
                                                <td>{{ trns('case_estimated_price') }}</td>
                                                <td>{{ trns('details') }}</td>
                                                <td>{{ trns('status') }}</td>
                                                <td>{{ trns('case_final_price') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->courtCases as $courtCases)
                                                <tr>
                                                    <td class="text-capitalize">{{ $courtCases->title }}</td>
                                                    <td class="text-capitalize">
                                                        {{ $courtCases->case_estimated_price }}
                                                    </td>
                                                    <td class="text-capitalize">{{ $courtCases->details }}</td>
                                                    <td class="text-capitalize">{{ $courtCases->status }}</td>
                                                    <td class="text-capitalize">{{ $courtCases->case_final_price }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">

                                <div class="table-responsive">
                                    @if ($obj->blogComments->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('blog post') }}</td>
                                                <td>{{ trns(key: 'comment') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{-- @dd($obj->blogComments) --}}
                                            @foreach ($obj->blogComments as $blogComment)
                                                <tr>
                                                    <td class="text-capitalize">
                                                        {{ Str::limit($blogComment->blog->body, 100) }}</td>
                                                    <td class="text-capitalize">{{ $blogComment->comment }}</td>
                                                    {{-- <td class="text-capitalize">{{ $blogComment->comment }}</td> --}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- blog reactions --}}
                <div class="tab-pane" id="tab-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">

                                <div class="table-responsive">
                                    @if ($obj->blogComments->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('blog post') }}</td>
                                                {{-- <td>{{ trns('blog post comment') }}</td> --}}
                                                <td>{{ trns('reaction') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{-- @dd($obj->blogComments) --}}
                                            {{-- @dd($obj->blogReactions) --}}
                                            Like count : {{ $obj->blogReactions->where('reaction', 'like')->count() }}
                                            Dislike count
                                            : {{ $obj->blogReactions->where('reaction', 'dislike')->count() }}
                                            <br>
                                            @foreach ($obj->blogReactions as $blogReaction)
                                                {{-- @foreach ($blogReaction->blogComments as $blogComment) --}}
                                                <tr>
                                                    <td class="text-capitalize">
                                                        {{ Str::limit($blogReaction->blog->body, 100) }}</td>
                                                    {{-- <td class="text-capitalize">{{ $blogReaction->blog->blogComments->first()->comment }}</td> --}}
                                                    <td class="text-capitalize">{{ $blogReaction->reaction }}</td>
                                                    {{-- <td class="text-capitalize">{{ $blogComment->comment }}</td> --}}
                                                </tr>
                                                {{-- @endforeach --}}
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div><!-- COL-END -->
    </div>


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

    @include('admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function () {
            $(' .table-bordered').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [10, 25, 50, 100],

            });
        });
    </script>



    <script>
        $('.tab-action').on('click', function (e) {
            $('.tab-action').removeClass('active show');
            $('.tab-pane').removeClass('active show');

            $(this).addClass('active show');
            let href = $(this).attr('href');
            $(`${href}`).addClass('active show');
        });


        function printDiv(divId) {
            var divContent = document.getElementById(divId).innerHTML;
            var printWindow = window.open('', '_blank', 'height=1200,width=1200');

            // Start writing the HTML structure
            printWindow.document.write('<html><head><title>Print</title>');

            // Copy all stylesheets from the original document to the new window
            Array.prototype.forEach.call(document.querySelectorAll("link[rel='stylesheet'], style"), function (link) {
                printWindow.document.write(link.outerHTML);
            });

            // Write internal styles specifically for printing
            printWindow.document.write(
                '<style>@media print { body * { visibility: hidden; } .printable, .printable * { visibility: visible; } .printable { position: absolute; left: 0; top: 0; width: 100%; }} .print-btn{ display: none; }</style>'
            );

            // Close the head tag and start the body
            printWindow.document.write('</head><body>');

            // Add the content to be printed
            printWindow.document.write('<div class="printable">' + divContent + '</div>');

            // Close the HTML structure
            printWindow.document.write('</body></html>');

            // Close the document to complete the writing process
            printWindow.document.close();

            // Ensure the styles and scripts are loaded before printing
            printWindow.onload = function () {
                // Delay printing to ensure styles are applied
                setTimeout(function () {
                    printWindow.focus(); // Focus the new window
                    printWindow.print(); // Trigger the print dialog
                    printWindow.close(); // Close the new window after printing
                }, 250); // Adjust delay as necessary
            };
        }

        editScript();


        function showIncentiveModal(routeOfEdit) {
            $(document).on('click', '.incentiveBtn', function () {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function () {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        function showGetIncentiveModal(routeOfEdit) {
            $(document).on('click', '.getIncentiveBtn', function () {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function () {
                    $('#modal-body').load(url)
                }, 500)
            })
        }
    </script>
@endsection
