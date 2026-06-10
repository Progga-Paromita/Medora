<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {

        if (Auth::user()->is_role == 1) {
            return $next($request);
        } else {
            return redirect(url('/'));
        }

    } else {
        Auth::logout();
        return redirect(url('/'));
    }
}
}
