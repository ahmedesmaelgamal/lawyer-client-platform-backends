<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CustomRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::macro('customResource', function ($name, $controller, array $options = []) {
            Route::get($name, [$controller, 'index'])->name("$name.index")->middleware($options['middleware'] ?? []);
            Route::get("$name/create", [$controller, 'create'])->name("$name.create")->middleware($options['middleware'] ?? []);
            Route::post($name, [$controller, 'store'])->name("$name.store")->middleware($options['middleware'] ?? []);
            Route::get("$name/{id}", [$controller, 'show'])->name("$name.show")->middleware($options['middleware'] ?? []);
            Route::get("$name/{id}/edit", [$controller, 'edit'])->name("$name.edit")->middleware($options['middleware'] ?? []);
            Route::put("$name/{id}", [$controller, 'update'])->name("$name.update")->middleware($options['middleware'] ?? []);
            Route::delete("$name/{id}", [$controller, 'destroy'])->name("$name.destroy")->middleware($options['middleware'] ?? []);
            
            Route::post("$name/delete-selected", [
                'uses' => "$controller@deleteSelected",
                'as' => "$name.deleteSelected",
            ])->middleware($options['middleware'] ?? []);
            Route::post("$name/update-column-selected", [
                'uses' => "$controller@updateColumnSelected",
                'as' => "$name.updateColumnSelected",
            ])->middleware($options['middleware'] ?? []);
            Route::post("$name/update-column-selected-confirmation", [
                'uses' => "$controller@updateColumnSelectedForConfirmation",
                'as' => "$name.updateColumnSelectedForConfirmation",
            ])->middleware($options['middleware'] ?? []);
        });
    }

    public function register()
    {
        //
    }
}
