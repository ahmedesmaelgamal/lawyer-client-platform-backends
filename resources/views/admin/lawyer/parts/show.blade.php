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
                                    <i class="fe fe-arrow-{{ flang('left','right')}}"></i>
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
                                    {{-- @dd($obj) --}}
                                    <div class="user-wrap">
                                        <h4 class="mb-1 text-capitalize">{{ $obj->name }}</h4>
                                        <h4 class="mb-1 text-capitalize">
                                            {{-- getting the last court case update for that lawyer --}}
                                            {{ 'Last case update : ' . ($obj->courtCaseEvents->count() &&  $obj->courtCaseEvents->last()->courtCase->courtCaseUpdates->count() ? $obj->courtCaseEvents->last()->courtCase->courtCaseUpdates->last()->created_at->format('Y-m-d') : 'N/A') }}
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
                                                data-toggle="tab">{{ trns('work times') }}</a></li>
                                <li class=""><a href="#tab-4" class="tab-action"
                                                data-toggle="tab">{{ trns('notifications') }}</a></li>
                                <li class=""><a href="#tab-5" class="tab-action"
                                                data-toggle="tab">{{ trns('orders') }}</a></li>
                                <li class=""><a href="#tab-6" class="tab-action"
                                                data-toggle="tab">{{ trns('court cases') }}</a></li>
                                <li class=""><a href="#tab-12" class="tab-action"
                                                data-toggle="tab">{{ trns('Reports') }}</a></li>
                                <li class=""><a href="#tab-7" class="tab-action"
                                                data-toggle="tab">{{ trns('ad packages') }}</a></li>
                                <li class=""><a href="#tab-8" class="tab-action"
                                                data-toggle="tab">{{ trns('ads') }}</a></li>
                                <li class=""><a href="#tab-9" class="tab-action"
                                                data-toggle="tab">{{ trns('posts') }}</a></li>
                                <li class=""><a href="#tab-10" class="tab-action"
                                                data-toggle="tab">{{ trns('post comments') }}</a></li>
                                {{-- <li class=""><a href="#tab-11" class="tab-action"
                                                                data-toggle="tab">{{ trns('bog post comment replies') }}</a></li> --}}
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
                                    {{-- <button onclick="printDiv('tab-1')"
                                            class="btn btn-primary print-btn">{{ trns('print') }}</button> --}}

                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        {{-- <tbody class="col-lg-12 col-xl-4 p-0">
                                            <tr>
                                            <td class="text-capitalize"><strong>{{ trns('group_name') }} :</strong>
                                                {{ $obj->group ? $obj->group->name : trns('human_resource') }}</td>
                                        </tr>
                                        </tbody> --}}
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>{{ trns('Name') }} :</strong>
                                                {{ $obj->name??"" }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('phone') }} :</strong> {{ $obj->phone??"" }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('email') }} :</strong> {{ $obj->email??"" }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('level') }} :</strong> {{ $obj->level->title??"" }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('country') }} :</strong> {{ $obj->country->title??"" }}
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('city') }} :</strong> {{ $obj->city->title ??""}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('lawyer_id') }} :</strong> {{ $obj->lawyer_id??"" }}
                                            </td>
                                        </tr>
                                        </tbody>
                                        @if ($obj->lawyerAbout)
                                            <tbody class="col-lg-12 col-xl-4 p-0">
                                            <tr>
                                                <td><strong>{{ trns('about') }}
                                                        :</strong> {{ $obj->lawyerAbout->about??"" }}</td>
                                            </tr>
                                            </tbody>
                                            <tbody class="col-lg-12 col-xl-4 p-0">
                                            <tr>
                                                <td><strong>{{ trns('consultation_fee') }}
                                                        :</strong> {{ $obj->lawyerAbout->consultation_fee ?? ""}}</td>
                                            </tr>
                                            </tbody>
                                            <tbody class="col-lg-12 col-xl-4 p-0">
                                            <tr>
                                                <td><strong>{{ trns('attorney_fee') }}
                                                        :</strong> {{ $obj->lawyerAbout->attorney_fee ??"" }}</td>
                                            </tr>
                                            </tbody>

                                            <tbody class="col-lg-12 col-xl-4 p-0">
                                            <tr>
                                                <td><strong>{{ trns('office_address') }}
                                                        :</strong> {{ $obj->lawyerAbout->office_address??"" }}</td>
                                            </tr>
                                            </tbody>
                                        @endif
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('office') }}
                                                    :</strong> {{ $obj->office->name??"" }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-2">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('wallet transactions') }}</strong></h5>
                                    <button onclick="printDiv('tab-2')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
                                <div class="table-responsive">
                                    @if($obj->walletTransactions->count() > 0)

                                        {{-- @dd($obj->walletTransactions) --}}
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td> #</td>
                                                <td>{{ trns('credit') }}</td>
                                                <td class="text-center">
                                                    {{ trns('debit') }}
                                                </td>
                                                <td>{{ trns('court case') }}</td>
                                                <td class="text-center">
                                                    {{ trns('date') }}
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach ($obj->walletTransactions as $wallet)

                                                <tr>
                                                    <td class="text-capitalize">
                                                        {{ $loop->iteration  ?? "N/A"}}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $wallet->credit ?? "N/A"}}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $wallet->debit ?? "N/A" }}
                                                    </td>
                                                    {{-- @dd($obj->courtCaseEvents->first()->courtCase) --}}
                                                    <td class="text-capitalize">
                                                        {{ $obj->courtCaseEvents->first()->courtCase->title ?? "N/A"}}
                                                    </td>

                                                    <td class="text-capitalize">
                                                        {{ Carbon\Carbon::parse($wallet->date)->format('d-m-Y') ?? "N/A" }}
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


                <div class="tab-pane" id="tab-3">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('payroll_record') }}</strong></h5>
                                    <button onclick="printDiv('tab-3')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
                                <div class="table-responsive">
                                    {{-- @dd($obj) --}}
                                    {{-- @dd($obj->lawyerTimes); --}}
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>{{ trns('day') }}</td>
                                            <td>{{ trns('from') }}</td>
                                            <td>{{ trns('to') }}</td>
                                            <td>{{ trns('status') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($obj->lawyerTimes as $lawyerTime)
                                            <tr>
                                                <td class="text-capitalize">{{ $lawyerTime->day }}</td>
                                                <td class="text-capitalize">{{ Carbon\Carbon::parse($lawyerTime['from'])->format('H:i') }}</td>
                                                <td class="text-capitalize">{{ Carbon\Carbon::parse($lawyerTime['to'])->format('H:i') }}</td>
                                                <td class="text-capitalize">

                                                    @if ($lawyerTime->status == 'active')
                                                        <span class="badge badge-success">{{ trns('active') }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ trns('inactive') }}</span>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="tab-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('notifications') }}</strong></h5>
                                    <button onclick="printDiv('tab-4')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
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


                <div class="tab-pane" id="tab-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('orders') }}</strong></h5>
                                    <button onclick="printDiv('tab-5')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
                                <div class="table-responsive">
                                    @if ($obj->orders->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('product') }}</td>
                                                <td>{{ trns('qty') }}</td>
                                                <td>{{ trns('phone') }}</td>
                                                <td>{{ trns('address') }}</td>
                                                <td>{{ trns('total_price') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->orders as $order)
                                                <tr>
                                                    <td class="text-capitalize">{{ $order->marketProduct->title }}</td>
                                                    <td class="text-capitalize">{{ $order->qty }}</td>
                                                    <td class="text-capitalize">{{ $order->phone }}</td>
                                                    <td class="text-capitalize">{{ $order->address }}</td>
                                                    <td class="text-capitalize">{{ $order->total_price }}</td>
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


                <div class="tab-pane" id="tab-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('court cases') }}</strong></h5>
                                    <button onclick="printDiv('tab-6')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
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


                <div class="tab-pane" id="tab-7">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('ad packages') }}</strong></h5>
                                    <button onclick="printDiv('tab-7')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
                                <div class="table-responsive">
                                    @if ($obj->lawyerPackages->count() > 0)
                                        <table class="table table-bordered" id="lawyer-packages-table">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('package') }}</td>
                                                <td>{{ trns('ads limit') }}</td>
                                                <td>{{ trns('price') }}</td>
                                                <td>{{ trns(key: 'discount') }}</td>
                                                <td>{{ trns(key: 'start_date') }}</td>
                                                <td>{{ trns(key: 'end_date') }}</td>
                                                <td>{{ trns(key: 'number of ads uploaded') }}</td>
                                                <td>{{ trns(key: 'is_active') }}</td>
                                                <td>{{ trns(key: 'is_expired') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->lawyerPackages as $lawyerPackage)
                                                <tr>
                                                    <td class="text-capitalize">{{ $lawyerPackage->offerPackage->title }}</td>
                                                    <td class="text-capitalize">{{ $lawyerPackage->offerPackage->number_of_days }}</td>
                                                    <td class="text-capitalize">{{ $lawyerPackage->offerPackage->price }}</td>
                                                    <td class="text-capitalize">{{ $lawyerPackage->offerPackage->discount }}</td>
                                                    <td class="text-capitalize">{{ $lawyerPackage->start_date }}</td>
                                                    <td class="text-capitalize">{{ $lawyerPackage->end_date }}</td>
                                                    <td class="text-capitalize">{{ $lawyerPackage->number_of_bumps }}</td>
                                                    <td class="text-capitalize">
                                                        @if ($lawyerPackage->status == 'active')
                                                            <span
                                                                class="badge badge-success">{{ trns('active') }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{ trns('inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-capitalize">
                                                        @if ($lawyerPackage->is_expired == 'ongoing')
                                                            <span
                                                                class="badge badge-success">{{ trns('on_going') }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{ trns('expired') }}</span>
                                                        @endif
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


                <div class="tab-pane" id="tab-8">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('ads') }}</strong></h5>
                                    <button onclick="printDiv('tab-8')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
                                <div class="table-responsive">
                                    @if ($obj->lawyerPackages->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('package') }}</td>
                                                <td>{{ trns(key: 'image') }}</td>
                                                <td>{{ trns('from_date') }}</td>
                                                <td>{{ trns(key: 'to_date') }}</td>
                                                <td>{{ trns('status') }}</td>
                                                <td>{{ trns(key: 'ad_confirmation') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->ads as $ad)
                                                <tr>
                                                    <td class="text-capitalize">{{ $ad->offerPackage->title }}</td>
                                                    <td class="text-capitalize">'
                                                        {{--                                                        <img width="50" height="50" src="{{ getFile($ad->image) }}" alt="">--}}
                                                        <img alt="ad image" href="{{  getFile($obj->image) }} "
                                                             src="{{  getFile($obj->image) }} "
                                                             class="image-popup avatar avatar-md rounded-circle"
                                                             style="cursor:pointer;" width="100" height="100"
                                                             loading="lazy">
                                                    </td>
                                                    <td class="text-capitalize">{{ $ad->from_date }}</td>
                                                    <td class="text-capitalize">{{ $ad->to_date }}</td>
                                                    <td class="text-capitalize">
                                                        @if ($ad->status == 'active')
                                                            <span
                                                                class="badge badge-success">{{ trns('active') }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{ trns('inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-capitalize">
                                                        @if ($ad->ad_confirmation == 'requested')
                                                            <span
                                                                class="badge badge-primary">{{ trns('requested') }}</span>
                                                        @elseif ($ad->ad_confirmation == 'confirmed')
                                                            <span
                                                                class="badge badge-success">{{ trns('accepted') }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{ trns('rejected') }}</span>
                                                        @endif
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


                <div class="tab-pane" id="tab-9">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('blog posts') }}</strong></h5>
                                    <button onclick="printDiv('tab-9')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
                                <div class="table-responsive">
                                    @if ($obj->lawyerPackages->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('body') }}</td>
                                                <td>{{ trns(key: 'like_count') }}</td>
                                                <td>{{ trns('dislike_ecount') }}</td>
                                                <td>{{ trns('comments_ecount') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{-- @dd($obj->blogs) --}}
                                            @foreach ($obj->blogs as $blog)
                                                <tr>
                                                    <td class="text-capitalize">{{ $blog->body }}</td>
                                                    <td class="text-capitalize">{{ $blog->count_like }}</td>
                                                    <td class="text-capitalize">{{ $blog->count_dislike }}</td>
                                                    <td class="text-capitalize">{{ $blog->blogComments->count() }}</td>

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


                <div class="tab-pane" id="tab-10">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                {{-- <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('blog post comments') }}</strong></h5>
                                    <button onclick="printDiv('tab-10')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div> --}}
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
                                                    <td class="text-capitalize">{{ Str::limit($blogComment->blog->body, 100) }}</td>
                                                    <td class="text-capitalize">{{ $blogComment->comment }}</td>
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

                <div class="tab-pane" id="tab-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">

                                <div class="table-responsive">
                                    @if ($obj->LawyerReports->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>

                                                <tr>
                                                    <th>{{ trns('client') }}</th>
                                                    <th>{{ trns('description') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               @foreach ($obj->LawyerReports as $report)
                                                    <tr>
                                                        <td class="text-capitalize">
                                                            {{ $report->client->name ?? 'Unknown' }}
                                                        </td>
                                                        <td class="text-capitalize cursor-pointer"
                                                            id="report-{{ $report->id }}"
                                                            data-body="{{ $report->body }}"
                                                            onclick="showFullBody({{ $report->id }})">
                                                            {{ Str::limit($report->body, 100) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_reports') }}</h4>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>


                {{-- <div class="tab-pane" id="tab-11">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('blog post comment replies') }}</strong></h5>
                                    <button onclick="printDiv('tab-11')"
                                        class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div>
                                <div class="table-responsive">
                                    @if ($obj->lawyerPackages->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>{{ trns('blog post') }}</td>
                                                <td>{{ trns(key: 'comment') }}</td>
                                                <td>{{ trns('reply') }}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obj->blogCommentReplies as $blogCommentReply)
                                                <tr>
                                                    <td class="text-capitalize">{{ Str::limit($blogCommentReply->comment->blog->body) }}</td>
                                                    <td class="text-capitalize">{{ Str::limit($blogCommentReply->Comment->comment) }}</td>
                                                    <td class="text-capitalize">{{ Str::limit($blogCommentReply->reply) }}</td>

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


        // $(document).ready(function () {
        // //     // Function to activate a tab based on the href
        // //     function activateTab(href) {
        // //         $('.tab-action').removeClass('active show');
        // //         $('.tab-pane').removeClass('active show');
        // //         $(`a[href="${href}"]`).addClass('active show');
        // //         $(href).addClass('active show');
        // //     }
        // //
        // //     // Handle tab clicks
        //     $('.tab-action').on('click', function (e) {
        //         e.preventDefault(); // Prevent default link behavior
        //         let href = $(this).attr('href');
        //         localStorage.setItem('activeTab', href);
        //         activateTab(href);
        //     });
        // //
        // //     // Retrieve the active tab from local storage
        // //     let activeTab = localStorage.getItem('activeTab');
        // //     if (activeTab) {
        // //         activateTab(activeTab);
        // //     }
        // //
        // //     // Update pagination links to preserve active tab
        // //     function updatePaginationLinks(tabHref) {
        // //         $('.pagination a').each(function () {
        // //             let url = new URL($(this).attr('href'), window.location.origin);
        // //             url.searchParams.set('tab', tabHref.replace('#', ''));
        // //             $(this).attr('href', url.href);
        // //         });
        // //     }
        // //
        // //     // On page load, update pagination links based on the active tab
        // //     if (activeTab) {
        // //         updatePaginationLinks(activeTab);
        // //     }
        // //
        // //     // Check for tab parameter in URL and activate the corresponding tab
        // //     let urlParams = new URLSearchParams(window.location.search);
        // //     let tabParam = urlParams.get('tab');
        // //     if (tabParam) {
        // //         let tabHref = '#' + tabParam;
        // //         localStorage.setItem('activeTab', tabHref);
        // //         activateTab(tabHref);
        // //         updatePaginationLinks(tabHref);
        // //     }
        // //
        // //     // When pagination is clicked, preserve the active tab
        // //     $(document).on('click', '.pagination a', function (e) {
        // //         let currentTab = localStorage.getItem('activeTab');
        // //         if (currentTab) {
        // //             let url = new URL($(this).attr('href'), window.location.origin);
        // //             url.searchParams.set('tab', currentTab.replace('#', ''));
        // //             $(this).attr('href', url.href);
        // //         }
        // //     });
        // // });


        //
        // $('.tab-action').on('click', function(e) {
        //     $('.tab-action').removeClass('active show');
        //     $('.tab-pane').removeClass('active show');
        //
        //     $(this).addClass('active show');
        //     let href = $(this).attr('href');
        //     $(`${href}`).addClass('active show');
        //
        //     // Store the active tab in local storage
        //     localStorage.setItem('activeTab', href);
        // });


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
