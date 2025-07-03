<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        // ...existing code...

        return $next($request);
    }

    /**
     * Get the path to redirect to after login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Only redirect for non-JSON requests (web)
        if (!$request->expectsJson()) {
            return route('login');
        }
        // For API requests, do not redirect (return null)
        return null;
    }
}