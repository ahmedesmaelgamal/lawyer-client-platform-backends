<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $permission): Response
    {
        if (!Auth::check() || !Auth::user()->can($permission)) {
            return redirect()->back()->with('toastr', [
                'type'    => 'error', 
                'message' => trans("you_don't_have_permission_to_access_this_page"),
                'title'   => trans('Unauthorized')
            ]);
        }
        return $next($request);
    }
}
