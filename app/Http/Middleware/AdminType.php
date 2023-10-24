<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\AdminType as Middleware;
use Illuminate\Support\Facades\Auth;

class AdminType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$adminType)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $user = Auth::user();
        if (in_array($user->admin_type, $adminType)) {
           
            return $next($request);
        }
        return redirect('/dashboard');
    }
}
