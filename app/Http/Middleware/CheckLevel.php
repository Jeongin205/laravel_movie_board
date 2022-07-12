<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        switch ($role) {
            case 'admin':
            case 'user':
                if (auth()->user()->user_level < config("ext.user.user_level.roles.{$role}.level")) {
                    return redirect('/movie');
                }
                break;
            default:
                return redirect('/login');
                break;
        }

        return $next($request);
    }
}
