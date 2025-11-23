<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\Page;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Carbon::setLocale('ar');

        Route::macro('customResource', function ($name, $controller, array $options = []) {

            Route::resource($name, $controller, $options);

            Route::post("$name/delete-selected", [
                'uses' => "$controller@deleteSelected",
                'as' => "$name.deleteSelected",
            ]);
            Route::post("$name/update-column-selected", [
                'uses' => "$controller@updateColumnSelected",
                'as' => "$name.updateColumnSelected",
            ]);
            Route::post("$name/update-column-selected-confirmation", [
                'uses' => "$controller@updateColumnSelectedForConfirmation",
                'as' => "$name.updateColumnSelectedForConfirmation",
            ]);
        });

        Schema::defaultStringLength(191);

        \view()->composer('*', function ($view) {
            $view->with('setting', Setting::all());
        });
    }

    
}
