<?php

use App\Http\Controllers\Admin\{AdminController, AuthController, HomeController, SettingController, SliderController};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('adminHome');
});
Route::group(
    [
        //        'prefix' => LaravelLocalization::setLocale(),
        //        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::group(['prefix' => 'admin'], function () {
            Route::get('login', [AuthController::class, 'index'])->name('admin.login');
            Route::POST('login', [AuthController::class, 'login'])->name('admin.login');
            Route::post('/admin/2fa/verify', [AuthController::class, 'verify2fa'])->name('admin.2fa.verify');

            //forget password
            Route::get('showForgetPassword', [AuthController::class, 'showForgetPassword'])->name('admin.showForgetPassword');
            Route::POST('ForgetPassword', [AuthController::class, 'ForgetPassword'])->name('admin.forgetPassword');
            Route::post('adminVerifyOtp', [AuthController::class, 'verifyOtp'])->name('admin.verifyOtp');
            Route::post("adminUpdatePass", [AuthController::class, 'UpdatePassword'])->name("updateAdminPass");


            // dashboard elements
            Route::group(['middleware' => 'auth:admin'], function () {

                Route::get('/dashboard', [HomeController::class, 'index'])->name('adminHome');

                #============================ Admin ====================================
                Route::customResource('admins', AdminController::class, [
                    'middleware' => ['permission:read_admin_management']
                ]);

                Route::get('my_profile', [AdminController::class, 'myProfile'])->middleware(['permission:read_admin_management'])->name('myProfile');
                Route::get('my_profile/edit', [AdminController::class, 'editProfile'])->middleware(['permission:update_admin_management'])->name('myProfile.edit');
                Route::get('my_profile/edit_profile_image', [AdminController::class, 'editProfileImage'])->middleware(['permission:update_admin_management'])->name('myProfile.edit.image');
                Route::post('my_profile/update_profile_image', [AdminController::class, 'updateProfileImage'])->middleware(['permission:update_admin_management'])->name('myProfile.update.image');
                Route::post('my_profile/update', [AdminController::class, 'updateProfile'])->middleware(['permission:update_admin_management'])->name('myProfile.update');
                Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

                Route::customResource('lawyers', \App\Http\Controllers\Admin\LawyerController::class, ['middleware' => ['permission:read_lawyer_management']]);
                Route::customResource('countries', \App\Http\Controllers\Admin\CountryController::class, ['middleware' => ['permission:read_country_management']]);
                Route::customResource('cities', \App\Http\Controllers\Admin\CityController::class, ['middleware' => ['permission:read_city_management']]);
                Route::customResource('levels', \App\Http\Controllers\Admin\LevelController::class, ['middleware' => ['permission:read_level_management']]);
                Route::customResource('specialities', \App\Http\Controllers\Admin\SpecialityController::class, ['middleware' => ['permission:read_speciality_management']]);
                Route::customResource('lawyer_specialities', \App\Http\Controllers\Admin\LawyerSpecialityController::class, ['middleware' => ['permission:read_speciality_management']]);
                Route::customResource('rates', \App\Http\Controllers\Admin\RateController::class, ['middleware' => ['permission:read_rates_management']]);
                Route::customResource('court_cases', \App\Http\Controllers\Admin\CourtCaseController::class, ['middleware' => ['permission:read_court_case_management']]);
                Route::customResource('court_case_events', \App\Http\Controllers\Admin\CourtCaseEventController::class, ['middleware' => ['permission:read_court_case_management']]);
                Route::customResource('court_case_files', \App\Http\Controllers\Admin\CourtCaseFileController::class, ['middleware' => ['permission:read_court_case_management']]);
                Route::customResource('court_case_levels', \App\Http\Controllers\Admin\CourtCaseLevelController::class, ['middleware' => ['permission:read_court_case_management']]);
                Route::customResource('Case_Specializations', \App\Http\Controllers\Admin\CaseSpecializationController::class, ['middleware' => ['permission:read_case_specialization_management']]);
                Route::customResource('SubCase_Specializations', \App\Http\Controllers\Admin\SubCaseSpecializationsController::class, ['middleware' => ['permission:read_case_specialization_management']]);
                Route::post('changeStatusCaseSpecializations', [\App\Http\Controllers\Admin\CaseSpecializationController::class, 'updateColumnSelected'])->name('changeStatusCaseSpecializations');


                //Route::customResource('lawyer_wallets', \App\Http\Controllers\Admin\LawyerWalletController::class);

                Route::customResource('court_case_dues', \App\Http\Controllers\Admin\CourtCaseDueController::class, [
                    'middleware' => ['permission:read_court_case_management']
                ]);

                Route::customResource('court_case_update_files', \App\Http\Controllers\Admin\CourtCaseUpdateFilesController::class, [
                    'middleware' => ['permission:read_court_case_management']
                ]);

                Route::customResource('court_case_updates', \App\Http\Controllers\Admin\CourtCaseUpdateController::class, [
                    'middleware' => ['permission:read_court_case_management']
                ]);

                Route::customResource('court_case_cancellation', \App\Http\Controllers\Admin\CourtCaseCancellationController::class, [
                    'middleware' => ['permission:read_court_case_management']
                ]);

                Route::customResource('lawyer_times', \App\Http\Controllers\Admin\LawyerTimeController::class, [
                    'middleware' => ['permission:read_lawyer_times_management']
                ]);

                Route::customResource('market_products', \App\Http\Controllers\Admin\MarketProductController::class, [
                    'middleware' => ['permission:read_market_management']
                ]);

                Route::customResource('market_product_categories', \App\Http\Controllers\Admin\MarketProductCategoryController::class, [
                    'middleware' => ['permission:read_market_product_categories_management']
                ]);

                Route::customResource('market_offers', \App\Http\Controllers\Admin\MarketOfferController::class, [
                    'middleware' => ['permission:read_market_offers_management']
                ]);

                Route::customResource('orders', \App\Http\Controllers\Admin\OrderController::class, [
                    'middleware' => ['permission:read_orders_management']
                ]);

                Route::customResource('product_discounts', \App\Http\Controllers\Admin\ProductDiscountController::class, [
                    'middleware' => ['permission:read_product_discounts_management']
                ]);

                Route::customResource('contract_categorys', \App\Http\Controllers\Admin\ContractCategoryController::class, [
                    'middleware' => ['permission:read_contract_categorys_management']
                ]);

                Route::customResource('contract_files', \App\Http\Controllers\Admin\ContractFileController::class, [
                    'middleware' => ['permission:read_contract_files_management']
                ]);

                Route::customResource('blogs', \App\Http\Controllers\Admin\BlogController::class, [
                    'middleware' => ['permission:read_blogs_management']
                ]);

                Route::customResource('blog_actions', \App\Http\Controllers\Admin\BlogActionController::class, [
                    'middleware' => ['permission:read_blog_actions_management']
                ]);

                Route::customResource('blog_files', \App\Http\Controllers\Admin\BlogFileController::class, [
                    'middleware' => ['permission:read_blog_files_management']
                ]);

                Route::customResource('blog_comments', \App\Http\Controllers\Admin\BlogCommentController::class, [
                    'middleware' => ['permission:read_blog_comments_management']
                ]);

                Route::customResource('blog_comment_reply', \App\Http\Controllers\Admin\BlogCommentReplyController::class, [
                    'middleware' => ['permission:read_blog_comment_reply_management']
                ]);

                Route::get('contract_files_data/{id}', [\App\Http\Controllers\Admin\ContractFileController::class, 'showContracts'])->middleware(['permission:read_contract_files_management'])->name('contract_files_data');




                Route::post('/admin/clear-cache', [HomeController::class, 'clearCache'])->name('admin.clear_cache');



                // Route::customResource('settings', \App\Http\Controllers\Admin\SettingController::class);
                Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index')->middleware(["permission:read_settings_management"]);
                Route::get('secure_settings', [SettingController::class, 'secure_settings'])->name('secure_settings.index');
                Route::post('secure/update', [SettingController::class, 'secure_update'])->name('secure_settings.update');
                Route::post('secure/disable', [SettingController::class, 'secure_disable'])->name('secure_settings.disable');
                Route::get('activity_logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->middleware(["permission:read_activity_logs_management"])->name('activity_logs.index');
                Route::post('activity_logs.deleteSelected', [\App\Http\Controllers\Admin\ActivityLogController::class, 'deleteSelected'])->middleware(["permission:read_activity_logs_management"])->name('activity_logs.deleteSelected');
                Route::delete('activity_logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->middleware(["permission:read_activity_logs_management"])->name('activity_logs.destroy');
                Route::customResource('roles', \App\Http\Controllers\Admin\RoleController::class, [
                    'middleware' => ['permission:read_roles_management']
                ]);

                // Route::get('permissions', [\App\Http\Controllers\Admin\PermissionController::class,'index'])->name('permissions.index');
                Route::put('settings', action: [\App\Http\Controllers\Admin\SettingController::class, 'update'])->middleware(["permission:read_settings_management"])->name('settings.update');





                Route::customResource('device_tokens', \App\Http\Controllers\Admin\DeviceTokenController::class, [
                    'middleware' => ['permission:read_device_token_management']
                ]);

                Route::customResource('notifications', \App\Http\Controllers\Admin\NotificationController::class, [
                    'middleware' => ['permission:read_notification_management']
                ]);

                Route::customResource('chats', \App\Http\Controllers\Admin\ChatController::class, [
                    'middleware' => ['permission:read_chat_management']
                ]);

                Route::customResource('wallets', \App\Http\Controllers\Admin\WalletTransactionController::class, [
                    'middleware' => ['permission:read_wallet_management']
                ]);

                // Route::customResource('system_activities', \App\Http\Controllers\Admin\SystemActivityController::class);

                // Route::customResource('role_and_permissions', \App\Http\Controllers\Admin\RoleAndPermissionController::class);

                Route::post('ads/confirm', [\App\Http\Controllers\Admin\AdController::class, 'updateColumnSelectedForConfirmation'])->name('ads.confirm')->middleware(["permission:read_ad_management"]);

                Route::customResource('lawyer_abouts', \App\Http\Controllers\Admin\LawyerAboutController::class, [
                    'middleware' => ['permission:read_lawyer_about_management']
                ]);

                Route::customResource('Lawyer_Report', \App\Http\Controllers\Admin\LawyerReportsController::class, [
                    'middleware' => ['permission:read_lawyer_report_management']
                ]);
                // read_lawyer_report_management
                Route::customResource('clients', \App\Http\Controllers\Admin\ClientController::class, [
                    'middleware' => ['permission:read_client_management']
                ]);

                Route::customResource('ads', \App\Http\Controllers\Admin\AdController::class, [
                    'middleware' => ['permission:read_ad_management']
                ]);

                Route::customResource('user_points', \App\Http\Controllers\Admin\ClientPointController::class, [
                    'middleware' => ['permission:read_client_point_management']
                ]);

                Route::customResource('s_o_s_requests', \App\Http\Controllers\Admin\SOSRequestController::class, [
                    'middleware' => ['permission:read_s_o_s_request_management']
                ]);

                Route::customResource('document_categories', \App\Http\Controllers\Admin\DocumentCategoryController::class, [
                    'middleware' => ['permission:read_document_categorie_management']
                ]);

                Route::customResource('documents', \App\Http\Controllers\Admin\DocumentController::class, [
                    'middleware' => ['permission:read_document_management']
                ]);

                Route::customResource('community_categories', \App\Http\Controllers\Admin\CommunityCategoryController::class, [
                    'middleware' => ['permission:read_community_categorie_management']
                ]);

                Route::customResource('community_sub_categories', \App\Http\Controllers\Admin\CommunitySubCategoryController::class, [
                    'middleware' => ['permission:read_community_sub_categorie_management']
                ]);

                Route::customResource('community_services', \App\Http\Controllers\Admin\CommunityServiceController::class, [
                    'middleware' => ['permission:read_community_service_management']
                ]);

                Route::customResource('free_consultation_requests', \App\Http\Controllers\Admin\FreeConsultationRequestController::class, [
                    'middleware' => ['permission:read_free_consultation_request_management']
                ]);

                Route::customResource('free_consultation_replies', \App\Http\Controllers\Admin\FreeConsultationRepliesController::class, [
                    'middleware' => ['permission:read_free_consultation_reply_management']
                ]);

                Route::customResource('refuse_reasons', \App\Http\Controllers\Admin\RefuseReasonController::class, [
                    'middleware' => ['permission:read_refuse_reason_management']
                ]);

                Route::customResource('offer_packages', \App\Http\Controllers\Admin\OfferPackageController::class, [
                    'middleware' => ['permission:read_offer_package_management']
                ]);

                Route::customResource('lawyer_packages', \App\Http\Controllers\Admin\LawyerPackageController::class, [
                    'middleware' => ['permission:read_lawyer_package_management']
                ]);

                Route::resource('OtherApps', \App\Http\Controllers\Admin\OtherAppController::class);
                Route::post('changeStatusOtherApp', [\App\Http\Controllers\Admin\OtherAppController::class, 'changeStatus'])->name('changeStatusOtherApp');


                Route::customResource('email_otps', \App\Http\Controllers\Admin\EmailOtpController::class, [
                    'middleware' => ['permission:read_email_otp_management']
                ]);

                Route::customResource('blog_reactions', \App\Http\Controllers\Admin\BlogReactionController::class, [
                    'middleware' => ['permission:read_blog_reaction_management']
                ]);

                Route::customResource('court_case_historys', \App\Http\Controllers\Admin\CourtCaseHistoryController::class, [
                    'middleware' => ['permission:read_court_case_history_management']
                ]);

                Route::customResource('office_requests', \App\Http\Controllers\Admin\OfficeRequestController::class, [
                    'middleware' => ['permission:read_office_request_management']
                ]);

                Route::customResource('payment_logs', \App\Http\Controllers\Admin\PaymentLogController::class, [
                    'middleware' => ['permission:read_payment_log_management']
                ]);

                Route::customResource('withdraw_requests', \App\Http\Controllers\Admin\WithdrawRequestController::class, [
                    'middleware' => ['permission:read_withdraw_request_management']
                ]);
            });
        });
        #=======================================================================
        #============================ ROOT =====================================
        #=======================================================================
        Route::get('/clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('key:generate');
            Artisan::call('storage:link');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');
            return response()->json(['status' => 'success', 'code' => 1000000000]);
        });
    }
);



Route::customResource('office_requests', \App\Http\Controllers\Admin\OfficeRequestController::class);

Route::customResource('point_transactions', \App\Http\Controllers\Admin\PointTransactionController::class);


Route::customResource('case_specializations', \App\Http\Controllers\Admin\CaseSpecializationController::class);

Route::customResource('ٍ_sub_case_specializationss', \App\Http\Controllers\Admin\ٍSubCaseSpecializationsController::class);
