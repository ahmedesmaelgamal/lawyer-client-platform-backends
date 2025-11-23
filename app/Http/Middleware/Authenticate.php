<?php


        namespace App\Http\Middleware;

        use Illuminate\Auth\Middleware\Authenticate as Middleware;
        use Illuminate\Support\Facades\Auth;

        class Authenticate extends Middleware
        {

            protected function redirectTo($request)
            {
                if ($request->expectsJson()) {
                    return null;
                }

                // Default redirection for non-authenticated users
                return route('admin.login');



        }
}
