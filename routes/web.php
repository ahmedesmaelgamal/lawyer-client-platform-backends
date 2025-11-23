<?php


use App\Http\Controllers\Web\{
    AboutController,
    AuthController,
    CheckoutController,
    ContactUsController,
    MainController,
    GoogleController,
    HomeController,
    CityController,
    PartnerController,
    profileController,
    reservationController,
    HotelController,
    ReviewController,
    RoomsController,
    SupportController,
    BePartnerController,
    BePartnerTrustController,
    BePartnerFaqsController,
    BePartnerStepController,
    ProductController,
    StoreController
};

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::group(
//    [
//        'prefix' => LaravelLocalization::setLocale(),
//        'as' => 'web.',
//        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
//    ],
//    function () {
//    }
//);
