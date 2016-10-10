<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;

class HoursCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!isset($_COOKIE['hoursCheck'])) {
            setcookie('hoursCheck', true, time() + (3600 * 6));
            Artisan::call('tasks:pending');
        }

        return $next($request);
    }
}
