@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ trns('court_case_details') }}
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
                            <a href="{{ route('court_cases.index') }}" class="btn btn-success text-white addBtn">
                                <span>
                                    <i class="fe fe-arrow-{{ flang('left', 'right') }}"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-6">
                            <div class="wideget-user text-center">
                                <div class="wideget-user-desc">
                                    {{-- <div class="wideget-user-img">
                                        <img class="" src="{{ getFile($obj->image) }}" alt="img">
                                    </div> --}}
                                    <div class="user-wrap">
                                        <h4 class="mb-1 text-capitalize">{{ $obj->title }} ( {{ $obj->case_number }} )
                                        </h4>
                                        <br>




                                        {{-- @dd($obj) --}}
                                        {{-- @dd($obj->courtCaseEvents->where('court_case_id', $obj->id)->where('status', 'accepted')->first()) --}}
                                        {{-- @if ($obj->attendances->count() > 0) --}}
                                        {{-- {{ trns('lawyer') }} :
                                            {{ $obj->courtCaseEvents->where('court_case_id', $obj->id)->where('status', 'accepted')->first()->lawyer->name ?? 'N/A' }}
                                            <br>
                                            {{ trns('client') }} : {{ $obj->client->name ?? 'N/A' }}
                                            <br>
                                            {{ trns('last_update') }} :
                                            {{ $obj->courtCaseDues->last()?->created_at?->format('Y-m-d') ?? 'N/A' }}
                                            <br>
                                            {{ trns('court case status') }} : {{ $obj->status ?? 'N/A' }} --}}
                                        {{-- {{ Carbon\Carbon::parse($obj->attendances->first()->check_in)->diffForHumans() }} --}}
                                        {{-- @endif --}}
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted mb-4 w-100">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card bg-info img-card box-success-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h2 class="mb-0 number-font">{{ $obj->courtCaseUpdates->count() }}</h2>
                                                <p class="text-white mb-0">{{ trns('court_cases_updates_count') }}</p>
                                            </div>
                                            <div class="mr-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card bg-info img-card box-success-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h2 class="mb-0 number-font">
                                                    {{ \App\Models\CourtCaseEvent::where('status', 'accepted')->count() }}
                                                </h2>
                                                <p class="text-white mb-0">{{ trns('lawyers_involved_count') }}</p>
                                            </div>
                                            <div class="mr-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card bg-info img-card box-success-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h2 class="mb-0 number-font">
                                                    {{ \App\Models\CourtCaseEvent::count() }}
                                                </h2>
                                                <p class="text-white mb-0">{{ trns('court_case_events_count') }}</p>
                                            </div>
                                            <div class="mr-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card bg-info img-card box-success-shadow">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="text-white">
                                                <h2 class="mb-0 number-font">
                                                    {{ \App\Models\CourtCaseDue::count() }}
                                                </h2>
                                                <p class="text-white mb-0">{{ trns('court_case_dues_count') }}</p>
                                            </div>
                                            <div class="mr-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <div class="col-lg-12">
        <div class="card">
            <div class="wideget-user-tab">
                <div class="tab-menu-heading">
                    <div class="tabs-menu1">
                        <ul class="nav">


                            <li class=""><a href="#tab-1" class="tab-action active show"
                                    data-toggle="tab">{{ trns('court case') }}</a>

                            </li>

                            <li class=""><a href="#tab-2" class="tab-action  show"
                                    data-toggle="tab">{{ trns('court case events') }}</a>

                            </li>

                            <li class=""><a href="#tab-3" class="tab-action  show"
                                    data-toggle="tab">{{ trns('court case updates') }}</a>

                            </li>

                            <li class=""><a href="#tab-4" class="tab-action  show"
                                    data-toggle="tab">{{ trns('court case Dues') }}</a>

                            </li>
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
                            <div class="table-responsive">
                                {{-- @if ($obj->count() > 0) --}}
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>{{ trns('title') }}</td>
                                            <td>{{ trns('level') }}</td>
                                            <td>{{ trns('case_estimated_price') }}</td>
                                            <td>{{ trns('details') }}</td>
                                            <td>{{ trns('status') }}</td>
                                            <td>{{ trns('seen (last update)') }}</td>
                                            <td>{{ trns('case_final_price') }}</td>
                                            <td>{{ trns('speciality') }}</td>
                                            <td>{{ trns('client') }}</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($obj) --}}
                                        <tr>
                                            <td class="text-capitalize">{{ $obj->title ?? 'N/A' }}</td>
                                            <td class="text-capitalize">{{ $obj->speciality->level->title ?? 'N/A' }}
                                            </td>
                                            <td class="text-capitalize">{{ $obj->case_estimated_price ?? 'N/A' }}</td>
                                            <td class="text-capitalize">{{ Str::limit($obj->details, 100) ?? 'N/A' }}
                                            </td>
                                            <td class="text-capitalize">{{ $obj->status ?? 'N/A' }}</td>

                                            <td class="text-capitalize">
                                                @if ($obj->seen == '1')
                                                    <span class="badge badge-success">{{ trns('seen') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ trns('unseen') }}</span>
                                                @endif

                                            </td>
                                            <td class="text-capitalize">{{ $obj->case_final_price ?? 'N/A' }}</td>
                                            <td class="text-capitalize">{{ $obj->speciality->title ?? 'N/A' }}</td>
                                            <td class="text-capitalize">{{ $obj->client->name ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- @else
                                    <h4>{{ trns('no_data') }} </h4>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="tab-pane" id="tab-2">
                <div class="card">
                    <div class="card-body">
                        <div id="profile-log-switch">
                            <div class="table-responsive">
                                @if ($obj->courtCaseEvents->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            {{-- @dd($obj->courtCaseEvents->where('court_case_id', $obj->id)) --}}
                                            {{-- @foreach ($obj->courtCaseEvents->where('court_case_id', $obj->id) as $courtCaseEvent) --}}
                                            {{-- @endforeach --}}
                                            <tr>
                                                <td>{{ trns('lawyer') }}</td>
                                                <td>{{ trns('price') }}</td>
                                                <td>{{ trns('status') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @dd($obj) --}}
                                            @foreach ($obj->courtCaseEvents->where('court_case_id', $obj->id)->all() as $courtCaseEvent)
                                                <tr>
                                                    <td class="text-capitalize">{{ $courtCaseEvent->lawyer->name ?? '' }}
                                                    </td>
                                                    <td class="text-capitalize">{{ $courtCaseEvent->price ?? '' }}</td>
                                                    <td class="text-capitalize">{{ $courtCaseEvent->status ?? '' }}</td>
                                                    {{-- <td class="text-capitalize">
                                                    @if ($courtCaseEvent->status == 'active')
                                                        <span class="badge badge-success">{{ trns('active') }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ trns('inactive') }}</span>
                                                    @endif
                                                </td> --}}
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



            <div class="tab-pane" id="tab-3">
                <div class="card">
                    <div class="card-body">
                        <div id="profile-log-switch">

                            <div class="table-responsive">
                                @if ($obj->courtCaseUpdates->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>{{ trns('date') }}</td>
                                                <td>{{ trns('details') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @dd($obj) --}}
                                            @foreach ($obj->courtCaseUpdates as $courtCaseUpdte)
                                                <tr>
                                                    <td class="text-capitalize">{{ $courtCaseUpdte->date ?? 'N/A' }}</td>
                                                    <td class="text-capitalize">
                                                        {{ Str::limit($courtCaseUpdte->details, 150) ?? 'N/A' }}</td>
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

                            <div class="table-responsive">
                                @if ($obj->courtCaseDues->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>{{ trns('title') }}</td>
                                                <td>{{ trns(key: 'from_user') }}</td>
                                                <td>{{ trns(key: 'to_user') }}</td>
                                                <td>{{ trns(key: 'court_case_event_status') }}</td>
                                                <td>{{ trns(key: 'court_case_event_price') }}</td>
                                                <td>{{ trns(key: 'date') }}</td>
                                                <td>{{ trns(key: 'paid') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @dd($obj) --}}
                                            @foreach ($obj->courtCaseDues as $courtCaseDue)
                                                <tr>
                                                    <td class="text-capitalize">{{ $courtCaseDue->title ?? 'N/A' }}</td>
                                                    <td class="text-capitalize">
                                                        {{ $courtCaseDue->from_user_type == 'lawyer' ? ($courtCaseDue->fromLawyer->name ?? 'N/A') . ' ( ' . ($courtCaseDue->from_user_type ?? 'N/A') . ' ) ' : ($courtCaseDue->client->name ?? 'N/A') . '( ' . ($courtCaseDue->from_user_type ?? 'N/A') . ' )' }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ ($courtCaseDue->toLawyer->name ?? 'N/A') . ' ( ' . ($courtCaseDue->to_user_type ?? 'N/A') . ' ) ' }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $courtCaseDue->courtCaseEvent->status ?? 'N/A' }}</td>
                                                    <td class="text-capitalize">
                                                        {{ $courtCaseDue->courtCaseEvent->price ?? 'N/A' }}</td>
                                                    <td class="text-capitalize">
                                                        {{ Str::limit($courtCaseDue->date, 150) ?? 'N/A' }}</td>
                                                    <td class="text-capitalize">{{ $courtCaseDue->paid ?? 'N/A' }}</td>
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




            {{-- <div class="tab-content">
                <div class="tab-pane" id="tab-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('court cases') }}</strong></h5>
                                    <button onclick="printDiv('tab-6')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div>
                                <div class="table-responsive">
                                    @if ($obj->orders->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>{{ trns('status') }}</td>
                                                <td>{{ trns('price') }}</td>
                                                <td>{{ trns('court_case') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obj->courtCaseEvents as $courtCaseEvent)
                                                <tr>
                                                    <td class="text-capitalize">{{ $courtCaseEvent->status }}</td>
                                                    <td class="text-capitalize">{{ $courtCaseEvent->price }}</td>
                                                    <td class="text-capitalize">{{ $courtCaseEvent->courtCase->title }}</td>
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

            </div> --}}

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
        $(document).ready(function() {
            $(' .table-bordered').DataTable();
        });
    </script>


    <script>
        $('.tab-action').on('click', function(e) {
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
            Array.prototype.forEach.call(document.querySelectorAll("link[rel='stylesheet'], style"), function(link) {
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
            printWindow.onload = function() {
                // Delay printing to ensure styles are applied
                setTimeout(function() {
                    printWindow.focus(); // Focus the new window
                    printWindow.print(); // Trigger the print dialog
                    printWindow.close(); // Close the new window after printing
                }, 250); // Adjust delay as necessary
            };
        }

        editScript();


        function showIncentiveModal(routeOfEdit) {
            $(document).on('click', '.incentiveBtn', function() {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function() {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        function showGetIncentiveModal(routeOfEdit) {
            $(document).on('click', '.getIncentiveBtn', function() {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function() {
                    $('#modal-body').load(url)
                }, 500)
            })
        }
    </script>
@endsection
