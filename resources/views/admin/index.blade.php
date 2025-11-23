@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ trns('home') }}
@endsection
@section('page_name')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">

            <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
                <button class="btn btn-danger" id="clear_Cache_btn" data-bs-toggle="modal" data-bs-target="#clear_Cache">
                    <i class="fas fa-trash ml-2"></i>{{ trns('clear_Cache') }}
                </button>
            </div>

            <div class="card-title text-center ">
                <h2>{{ trns('general_statistics') }}</h2>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $admins->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('admins_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $clients->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('clients_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $lawyers->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('lawyers_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $courtCases->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('court_cases_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $courtCases->where('status', 'accepted')->count() }}
                                    </h2>
                                    <p class="text-white mb-0">{{ trns('accepted_court_cases_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $courtCases->where('status', 'rejected')->count() }}
                                    </h2>
                                    <p class="text-white mb-0">{{ trns('rejected_court_cases_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $marketProducts->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('market_products_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $marketProducts->where('status', 'active')->count() }}
                                    </h2>
                                    <p class="text-white mb-0">{{ trns('active_market_products_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">
                                        {{ $marketProducts->where('status', 'inactive')->count() }}
                                    </h2>
                                    <p class="text-white mb-0">{{ trns('inactive_market_products_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $orders->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('orders_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $orders->where('status', 'new')->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('new_orders_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $orders->where('status', 'completed')->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('copleted_orders_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $ads->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('ads_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $ads->where('status', 'active')->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('active_ads_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                    <div class="card bg-primary-gradient img-card box-success-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">{{ $ads->where('status', 'inactive')->count() }}</h2>
                                    <p class="text-white mb-0">{{ trns('inactive_ads_count') }}</p>
                                </div>
                                <div class="mr-auto">
                                    <i class="fe fe-shield text-white fs-30 ml-2 mt-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body w-100">
            <div class="row d-flex justify-content-between ">

                {{-- the cart cases chart --}}
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 w-100 d-flex flex-column align-items-center">
                    <div class="w-100 text-center"> <!-- Still need text-center -->
                        <h2>{{ trns('court_cases') }}</h2>
                    </div>
                    <canvas id="chartContainer1" width="400" height="400"></canvas>
                </div>

                {{-- the shop orders chart --}}
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 w-100 d-flex flex-column align-items-center">
                    <div class="w-100 text-center"> <!-- Still need text-center -->
                        <h2>{{ trns('orders') }}</h2>
                    </div>
                    <canvas id="chartContainer2" width="400" height="400"></canvas>
                </div>

                {{-- the loyers count chart --}}
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 w-100 d-flex flex-column align-items-center">
                    <div class="w-100 text-center"> <!-- Still need text-center -->
                        <h2>{{ trns('loyers_count') }}</h2>
                    </div>
                    <canvas id="chartContainer3" width="400" height="400"></canvas>
                </div>

                {{-- the client count chart --}}
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 w-100 d-flex flex-column align-items-center">
                    <div class="w-100 text-center"> <!-- Still need text-center -->
                        <h2>{{ trns('clients_count') }}</h2>
                    </div>
                    <canvas id="chartContainer4" width="400" height="400"></canvas>
                </div>

                {{-- the ads count chart --}}
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 w-100 d-flex flex-column align-items-center">
                    <div class="w-100 text-center"> <!-- Still need text-center -->
                        <h2>{{ trns('ads_count') }}</h2>
                    </div>
                    <canvas id="chartContainer5" width="400" height="400"></canvas>
                </div>

                {{-- the total price ads chart --}}
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 w-100 d-flex flex-column align-items-center">
                    <div class="w-100 text-center"> <!-- Still need text-center -->
                        <h2>{{ trns('Total profits') }}</h2>
                    </div>
                    <canvas id="chartContainer6" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="clear_Cache" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trns('clear_Cache') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ trns('are_you_sure_you_want_to_clear_cache') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
                        {{ trns('close') }}
                    </button>
                    <button type="button" class="btn btn-danger" id="confirm_clear_cache">
                        {{ trns('clear_Cache') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('ajaxCalls')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).on('click', '#confirm_clear_cache', function() {
            $.ajax({
                url: "{{ route('admin.clear_cache') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr.success("{{ trns('cache_cleared_successfully') }}");
                    $('#clear_Cache').modal('hide');
                },
                error: function() {
                    toastr.error("{{ trns('something_went_wrong') }}");
                }
            });
        });
    </script>
    <script>
        window.onload = function() {
            // PHP Data Passed to JavaScript
            var months = @json(lang() == 'ar' ? $chartData['months_ar'] : $chartData['months_en']);
            var courtCases = @json($chartData['courtCases']);
            var orders = @json($chartData['orders']);
            var loyersCount = @json($chartData['loyersCount']);
            var clientsCount = @json($chartData['clientsCount']);
            var adsCount = @json($chartData['adsCount']);
            var offerPackageTotalPrice = @json($chartData['offerPackageTotalPrice']);

            var ctx1 = document.getElementById('chartContainer1').getContext('2d');
            var chart1 = new Chart(ctx1, {
                type: 'line', // Use 'line' type for an area chart effect
                data: {
                    labels: months, // Use dynamic months for x-axis
                    datasets: [{
                        label: "{{ trns('court_cases') }}",
                        data: courtCases, // Use dynamic court cases data
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Fill color for the area under the line
                        borderWidth: 1,
                        fill: true, // This enables the area chart (fills the area beneath the line)
                        tension: 0.5 // This will make the line smoother, adjust for desired smoothness
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var ctx2 = document.getElementById('chartContainer2').getContext('2d');
            var chart2 = new Chart(ctx2, {
                type: 'line', // Use 'line' type for an area chart effect
                data: {
                    labels: months, // Use dynamic months for x-axis
                    datasets: [{
                        label: "{{ trns('orders') }}",
                        data: orders, // Use dynamic orders data
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Fill color for the area under the line
                        borderWidth: 1,
                        fill: true, // This enables the area chart (fills the area beneath the line)
                        tension: 0.5 // This will make the line smoother, adjust for desired smoothness
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            {{-- the loyers count chart  --}}

            var ctx3 = document.getElementById('chartContainer3').getContext('2d');
            var chart3 = new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: "{{ trns('loyers_count') }}",
                        data: loyersCount,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            {{-- the clients count chart  --}}

            var ctx4 = document.getElementById('chartContainer4').getContext('2d');
            var chart4 = new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: "{{ trns('clients_count') }}",
                        data: clientsCount,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            {{-- the ads count chart  --}}

            var ctx5 = document.getElementById('chartContainer5').getContext('2d');
            var chart5 = new Chart(ctx5, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: "{{ trns('ads_count') }}",
                        data: adsCount,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            {{-- the total ads price chart  --}}

            var ctx6 = document.getElementById('chartContainer6').getContext('2d');
            var chart6 = new Chart(ctx6, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: "{{ trns('Total profits') }}",
                        data: offerPackageTotalPrice,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endsection
