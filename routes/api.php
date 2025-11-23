<?php

use App\Http\Controllers\Api\ChatRoom\ChatRoomController;
use App\Http\Controllers\Api\Community\CommunityController;
use App\Http\Controllers\Api\Lawyer\AdController;
use App\Http\Controllers\Api\Lawyer\Auth\AuthController as LawyerAuthController;
use App\Http\Controllers\Api\Client\Auth\AuthController as ClientAuthController;
use App\Http\Controllers\Api\Client\LawyerController as ClientLawyerController;
use App\Http\Controllers\Api\Client\LawyerReportsController;
use App\Http\Controllers\Api\Client\CourtCaseController as ClientCourtCaseController;
use App\Http\Controllers\Api\Lawyer\MarketProductController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\Client\MainController as ClientMainController;
use App\Http\Controllers\Api\Lawyer\MainController as LawyerMainController;
use App\Http\Controllers\Api\Lawyer\CourtCaseController;
use App\Http\Controllers\Api\paymob\PaymobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'lang', 'prefix' => 'v1'], function () {

    //-------------------------------------------------------------------------
    // Lawyer Routes
    //-------------------------------------------------------------------------
    Route::group(['prefix' => 'lawyer'], function () {

        // auth route
        Route::group(['prefix' => 'auth'], function () {
            Route::post('register', [LawyerAuthController::class, 'register']);
            Route::post('login', [LawyerAuthController::class, 'login']);
            Route::post('sendOtp', [LawyerAuthController::class, 'sendOtp']);
            Route::post('checkOtp', [LawyerAuthController::class, 'checkOtp']);
            Route::post('resetPassword', [LawyerAuthController::class, 'resetPassword']);
        });

        // authenticated routes
        Route::group(['middleware' => 'jwt'], function () {
            Route::get('profile', [LawyerAuthController::class, 'profile']);
            Route::post('profile/update', [LawyerAuthController::class, 'updateProfile']);
            Route::get('lawyerWorkTimes', [LawyerAuthController::class, 'getLawyerWorkTimes']);
            Route::post('lawyerWorkTimes', [LawyerAuthController::class, 'lawyerWorkTimes']);
            Route::post('lawyerWorkTimes/updateStatus', [LawyerAuthController::class, 'updateLawyerWorkTimesStatus']);
            Route::post('profile/updateEmail', [LawyerAuthController::class, 'updateEmail']);
            Route::get('logout', [LawyerAuthController::class, 'logout']);
            Route::post('changePassword', [LawyerAuthController::class, 'changePassword']);
            Route::post('changeType', [LawyerMainController::class, 'changeType']);

            //main controller
            Route::get('getHome', [LawyerMainController::class, 'home']);
            Route::get('searchLawyer', [LawyerMainController::class, 'searchLawyer']);
            Route::delete('deleteLawyerById', [LawyerMainController::class, 'deleteLawyerById']);
            Route::get('getOfficeTeam', [LawyerMainController::class, 'getOfficeTeam']);
            Route::post('sendOfficeRequest', [LawyerMainController::class, 'sendOfficeRequest']);
            Route::get('getOffficeRequest', [LawyerMainController::class, 'getOfficeRequest']);
            Route::post('sendOfficeResponse', [LawyerMainController::class, 'sendOfficeResponse']);
            Route::post('deleteLawyerFromOffice', [LawyerMainController::class, 'deleteLawyerFromOffice']);
            Route::post('deleteLawyerFromOfficeResponse', [LawyerMainController::class, 'deleteLawyerFromOfficeResponse']);
            Route::post('deleteLawyerFromOfficeRequest', [LawyerMainController::class, 'deleteLawyerFromOfficeRequest']);
            Route::get('getWalletTransactions', [LawyerMainController::class, 'getWalletTransactions']);
            Route::post('withdrawRequest', [LawyerMainController::class, 'withdrawRequest']);

            //marketProduct controller
            Route::get('getMarketProduct/{id}', [MarketProductController::class, 'getMarketProduct']);
            Route::get('getAllMarketProducts', [MarketProductController::class, 'getAllMarketProducts']);
            Route::post('addOrder', [MarketProductController::class, 'addOrder']);
            Route::get('getOrders', [MarketProductController::class, 'getOrders']);
            Route::get('getMarketProductHome', [MarketProductController::class, 'getMarketProductHome']);


            //ad controller
            Route::post('addAdOfferPackage', [AdController::class, 'addAdOfferPackage']);
            //            Route::post('addAdToPackageLawyer', [AdController::class, 'addAdToPackageLawyer']);
            Route::get('getAds', [AdController::class, 'getAds']);
            Route::get('getAdOfferPackages', [AdController::class, 'getAdOfferPackages']);
            Route::get('getLawyerPackageAds/{id}', [AdController::class, 'getLawyerPackageAds']);
            Route::get('getLawyerAdPackages', [AdController::class, 'getLawyerAdPackages']);
            Route::post('addAdToLawyerPackage', [AdController::class, 'addAdToLawyerPackage']);
            Route::post('addAdOfferPackageToLawyer', [AdController::class, 'addAdOfferPackageToLawyer']);


            Route::get('getCourtCase/{id}', [CourtCaseController::class, 'courtCase']);
            Route::post('updateCourtCaseUpdate/{id}', [CourtCaseController::class, 'updateCourtCaseUpdate']);
            Route::delete('deleteCourtCaseUpdateFile/{id}', [CourtCaseController::class, 'deleteCourtCaseUpdateFile']);
            Route::delete('deleteCourtCaseUpdate/{id}', [CourtCaseController::class, 'deleteCourtCaseUpdate']);
            Route::post('transferCourtCaseToAnotherLawyerRequest', [CourtCaseController::class, 'transferCourtCaseToAnotherLawyerRequest']);
            Route::post('transferCourtCaseToAnotherLawyerResponse', [CourtCaseController::class, 'transferCourtCaseToAnotherLawyerResponse']);
            Route::get('getAllTransferCourtCases', [CourtCaseController::class, 'getAllTransferCourtCases']);
            Route::get('getAllContributionCourtCases', [CourtCaseController::class, 'getAllContributionCourtCases']);

            Route::get('courtCaseTransferRequest', [CourtCaseController::class, 'courtCaseTransferRequest']);
            Route::get('courtCaseContributionRequest', [CourtCaseController::class, 'courtCaseContributionRequest']);

            Route::post('addLawyerToCourtCaseRequest', [CourtCaseController::class, 'addLawyerToCourtCaseRequest']);
            Route::post('addLawyerToCourtCaseResponse', [CourtCaseController::class, 'addLawyerToCourtCaseResponse']);

            Route::get('getMyCourtCases', [CourtCaseController::class, 'myCourtCases']);
            Route::post('addEventCourtCase', [CourtCaseController::class, 'addEventCourtCase']);
            Route::get('deleteEventCourtCase/{id}', [CourtCaseController::class, 'deleteEventCourtCase']);
            Route::post('actionCourtCase', [CourtCaseController::class, 'actionCourtCase']);
            Route::post('addCourtCaseDues', [CourtCaseController::class, 'addCourtCaseDues']);
            Route::post('addNewUpdate', [CourtCaseController::class, 'addNewUpdate']);

            Route::post('deleteAccountRequest', [LawyerMainController::class, 'deleteAccountRequest']);

            //notifications
            Route::get('getNotifications', [LawyerMainController::class, 'getNotifications']);
        });
    });

    //-------------------------------------------------------------------------
    // Client Routes
    //-------------------------------------------------------------------------
    Route::group(['prefix' => 'client'], function () {

        // auth route
        Route::group(['prefix' => 'auth'], function () {
            Route::post('register', [ClientAuthController::class, 'register']);
            Route::post('login', [ClientAuthController::class, 'login']);
            Route::post('sendOtp', [ClientAuthController::class, 'sendOtp']);
            Route::post('checkOtp', [ClientAuthController::class, 'checkOtp']);
            Route::post('resetPassword', [ClientAuthController::class, 'resetPassword']);
        });

        // authenticated routes
        Route::group(['middleware' => 'jwt-client'], function () {
            //Lawyers controller
            Route::get('getHome', [ClientMainController::class, 'home']);
            Route::get('getLawyerOffers', [ClientMainController::class, 'getLawyerOffers']);
            Route::get('getLawyers', [ClientMainController::class, 'getLawyers']);
            Route::get('lawyerDetails/{id}', [ClientLawyerController::class, 'lawyerDetails']);
            Route::post('changePassword', [ClientAuthController::class, 'changePassword']);

            // court case controller
            Route::post('addPrivateCase', [ClientCourtCaseController::class, 'addPrivateCase']);
            Route::post('addNewCourtCase', [ClientCourtCaseController::class, 'addNewCourtCase']);
            Route::get('getCourtCases/{type}', [ClientCourtCaseController::class, 'getCourtCases']);
            Route::post('cancelCourtCase', [ClientCourtCaseController::class, 'cancelCourtCase']);
            Route::post('finishCourtCase', [ClientCourtCaseController::class, 'finishCourtCase']);
            Route::get('getCourtCase/{id}', [ClientCourtCaseController::class, 'getCourtCase']);
            Route::post('actionEvent', [ClientCourtCaseController::class, 'actionEvent']);
            Route::get('getCourtCaseDeus/{id}', [ClientCourtCaseController::class, 'getCourtCaseDeus']);
            Route::get('payDue/{id}', [ClientCourtCaseController::class, 'payDue']);
            Route::post('transferCourtCaseToAnotherLawyerResponse', [ClientCourtCaseController::class, 'transferCourtCaseToAnotherLawyerResponse']);
            Route::get('courtCaseTransferRequest', [ClientCourtCaseController::class, 'courtCaseTransferRequest']);
            Route::get('getAllTransferCourtCases', [ClientCourtCaseController::class, 'getAllTransferCourtCases']);

            Route::post('addSosRequest', [ClientMainController::class, 'addSosRequest']);
            Route::get('sosLawyers', [ClientMainController::class, 'sosLawyers']);


            Route::get('getCommunityServiceCategories', [ClientMainController::class, 'getCommunityServiceCategories']);
            //            Route::get('getCommunityServiceSubCategories/{id}', [ClientMainController::class, 'getCommunityServiceSubCategories']);//we pass community category id as a parameter
            Route::get('getCommunityService/{id}', [ClientMainController::class, 'getCommunityService']); //we pass community sub category id as a parameter


            Route::get('getContractCategories', [ClientMainController::class, 'getContractCategories']);
            Route::get('getContractFiles', [ClientMainController::class, 'getContractFiles']);
            Route::get('getContracts', [ClientMainController::class, 'getContracts']);
            Route::post("contractFileCheckout" , [ClientMainController::class, 'contractFileCheckout']);


            Route::post('addClientPoints', [ClientMainController::class, 'addClientPoints']);


            // auth controller
            Route::get('profile', [ClientAuthController::class, 'profile']);
            Route::post('profile/update', [ClientAuthController::class, 'updateProfile']);
            Route::post('profile/updateEmail', [ClientAuthController::class, 'updateEmail']);
            Route::get('logout', [ClientAuthController::class, 'logout']);


            //commercial code
            Route::get('profile/getCommercialCode', [ClientMainController::class, 'getCommercialCode']);
            Route::get('profile/getClientPoints', [ClientMainController::class, 'getClientPoints']);
            Route::post('profile/addClientPoints', [ClientMainController::class, 'addClientPoints']);
            Route::post('profile/pointTransaction', [ClientMainController::class, 'pointTransaction']);


            // wallet
            Route::get('getWalletTransactions', [ClientMainController::class, 'getWalletTransactions']);
            Route::post('withdrawRequest', [ClientMainController::class, 'withdrawRequest']);
            // notifications
            Route::get('getNotifications', [ClientMainController::class, 'getNotifications']);
            // make report from user to lawyer
            Route::post('makeReport', [LawyerReportsController::class, 'MakeReport']);
        });
    });


    //-------------------------------------------------------------------------
    // Community Routes
    //-------------------------------------------------------------------------
    Route::group(['prefix' => 'community'], function () {
        Route::get('getPosts', [CommunityController::class, 'getPosts']);
        Route::get('getPostComments/{id}', [CommunityController::class, 'getPostComments']);
        Route::get('getCommentReply/{id}', [CommunityController::class, 'getCommentReply']);
        Route::get('getReplyReplies/{id}', [CommunityController::class, 'getReplyReplies']);
        Route::post('addPost', [CommunityController::class, 'addPost']);
        Route::post('addPostAction', [CommunityController::class, 'addPostAction']);
        Route::post('addPostComment', [CommunityController::class, 'addPostComment']);
        Route::get('deletePostComment/{id}', [CommunityController::class, 'deletePostComment']);
        Route::get('deletePostCommentReply/{id}', [CommunityController::class, 'deletePostCommentReply']);
        Route::get('deletePost/{id}', [CommunityController::class, 'deletePost']);
        Route::post('addPostCommentReply', [CommunityController::class, 'addPostCommentReply']);
    });


    //-------------------------------------------------------------------------
    // Chat Room Routes
    //-------------------------------------------------------------------------

    Route::group(['prefix' => 'chat'], function () {
        Route::get('getChatRooms', [ChatRoomController::class, 'getChatRooms']);
        Route::post('createChatRoom', [ChatRoomController::class, 'addChatRoom']);
        Route::post('sendMessage', [ChatRoomController::class, 'addMessage']);
    });


    //-------------------------------------------------------------------------
    // General Routes (No Authentications)
    //-------------------------------------------------------------------------
    Route::group(['prefix' => 'general'], function () {
        Route::get('specialitiesById/{id}', [MainController::class, 'specialitiesById']);
        Route::get('getLevels', [MainController::class, 'levels']);
        Route::get('getCountries', [MainController::class, 'country']);
        Route::get('getCities/{id}', [MainController::class, 'city']);
        Route::get('getLawyers', [MainController::class, 'getLawyers']);
        Route::get('getSpecialities', [MainController::class, 'specialities']);
        Route::get('getCancelReasons', [MainController::class, 'getCancelReasons']);
        Route::get('getFinishReasons', [MainController::class, 'getFinishReasons']);
        Route::get('getSettings', [MainController::class, 'getSettings']);
        Route::get('getCourtCaseLevels', [MainController::class, 'getCourtCaseLevel']);
        Route::get('getCaseSpecializations', [MainController::class, 'getCaseSpecializations']);
        Route::get('getSubCaseSpecializations/{id}', [MainController::class, 'getSubCaseSpecializations']);
    });

    //-------------------------------------------------------------------------
    // paymob Routes
    //-------------------------------------------------------------------------
    Route::group(['prefix' => 'paymob'], function () {
        Route::get('pay', [PaymobController::class, 'pay']);
        Route::get('paymentCallBack', [PaymobController::class, 'paymentCallBack']);
    });


    Route::get('testFcm', [MainController::class, 'testFcm']);
    Route::get('testFcmResponse', [MainController::class, 'testFcmResponse']);
});
