<?php

namespace App\Services\Admin;

use App\Models\Ad;
use App\Models\Admin;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\FreeConsultationRequest as ObjModel;
use App\Models\Lawyer;
use App\Models\LawyerPackage;
use App\Models\MarketProduct;
use App\Models\OfferPackage;
use App\Models\Order;
use App\Services\BaseService;

class HomeService extends BaseService
{
    //    protected string $folder = 'admin/admin';
    //    protected string $route = 'adminHome';

    public function __construct(
        ObjModel $objModel,
        protected CourtCase $courtCase,
        protected Client $client,
        protected Admin $admin,
        protected Ad $ad,
        protected Lawyer $lawyer,
        protected Order $order,
        protected MarketProduct $marketProduct,
        protected OfferPackage $offerPackage,
        protected LawyerPackage $lawyerPackage
    ) {
        parent::__construct($objModel);
    }

    public function index()
    {
        // Fetching all relevant data
        $admins = $this->admin->all();
        $clients = $this->client->all();
        $lawyers = $this->lawyer->all();
        $courtCases = $this->courtCase->all();
        $marketProducts = $this->marketProduct->all();
        $orders = $this->order->all();
        $ads = $this->ad->all();
        $offerPackage = $this->offerPackage->all();

        // Initialize empty arrays for counts and months
        $courtCaseCount = [];
        $orderCount = [];
        $allMonths = [];
        $loyersCount = [];
        $clientsCount = [];
        $adsCount = [];
        $offerPackageTotalPrice = [];

        // Get current locale
        $locale = app()->getLocale();

        // Loop through the last 12 months and get the count for each month
        for ($i = 0; $i < 12; $i++) {
            $startOfMonth = now()->subMonths($i); // Get the start of the month, $i months ago
            $month = $startOfMonth->month; // Extract the month (1-12)
            $year = $startOfMonth->year; // Extract the year

            // Store the month and year in $allMonths
            $allMonths[$i]['month'] = $month;
            $allMonths[$i]['year'] = $year;

            // Count court cases for the current month
            $courtCaseCount["$year,$month"] = $this->courtCase->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            // Count orders for the current month
            $orderCount["$year,$month"] = $this->order->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();
        }


        $loyersCount["$year,$month"] = $this->lawyer->whereYear('created_at', now()->year)
            ->count();

        $clientsCount["$year,$month"] = $this->client->whereYear('created_at', now()->year)
            ->count();

        $adsCount["$year,$month"] = $this->ad->whereYear('created_at', now()->year)
            ->count();

        $packages_ids = $this->lawyerPackage->whereYear('created_at', now()->year)->pluck('id')->toArray();

        $offerPackageTotalPrice["$year,$month"] = $this->offerPackage->whereIn('id', $packages_ids)
            ->selectRaw('SUM(price - discount) as total')->value('total') ?? 0;

            // dd($offerPackageTotalPrice);

        // Prepare data for chart display with both English and Arabic month names
        $chartData = [
            'months_en' => array_map(function ($item) {
                // Format months as 'Month Year' in English
                \Carbon\Carbon::setLocale('en');
                return \Carbon\Carbon::createFromDate($item['year'], $item['month'], 1)->translatedFormat('F Y');
            }, $allMonths),
            'months_ar' => array_map(function ($item) {
                // Format months as 'Month Year' in Arabic
                \Carbon\Carbon::setLocale('ar');
                return \Carbon\Carbon::createFromDate($item['year'], $item['month'], 1)->translatedFormat('F Y');
            }, $allMonths),
            'courtCases' => array_map(function ($item) use ($courtCaseCount) {
                return $courtCaseCount["{$item['year']},{$item['month']}"] ?? 0;
            }, $allMonths),
            'orders' => array_map(function ($item) use ($orderCount) {
                return $orderCount["{$item['year']},{$item['month']}"] ?? 0;
            }, $allMonths),
            'loyersCount' => array_map(function ($item) use ($loyersCount) {
                return $loyersCount["{$item['year']},{$item['month']}"] ?? 0;
            }, $allMonths),
            'clientsCount' => array_map(function ($item) use ($clientsCount) {
                return $clientsCount["{$item['year']},{$item['month']}"] ?? 0;
            }, $allMonths),
            'adsCount' => array_map(function ($item) use ($adsCount) {
                return $adsCount["{$item['year']},{$item['month']}"] ?? 0;
            }, $allMonths),
            'offerPackageTotalPrice' => array_map(function ($item) use ($offerPackageTotalPrice) {
                return $offerPackageTotalPrice["{$item['year']},{$item['month']}"] ?? 0;
            }, $allMonths),
        ];




        // Return data to the view
        return view('admin.index', [
            'admins' => $admins,
            'clients' => $clients,
            'lawyers' => $lawyers,
            'loyersCount' => $loyersCount,
            'courtCases' => $courtCases,
            'marketProducts' => $marketProducts,
            'courtCaseCount' => $courtCaseCount,
            'orderCount' => $orderCount,
            'orders' => $orders,
            'ads' => $ads,
            'chartData' => $chartData,
            'currentLocale' => $locale
        ]);
    }
}
