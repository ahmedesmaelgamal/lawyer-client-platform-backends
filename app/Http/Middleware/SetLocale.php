<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{

    public function handle(Request $request, Closure $next)
    {
        app()->setLocale('ar');

        if (session()->has('system_language')) {
            app()->setLocale(session('system_language'));
        }
        return $next($request);

    }
}
