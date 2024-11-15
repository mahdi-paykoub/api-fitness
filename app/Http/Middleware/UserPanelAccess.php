<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPanelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (json_decode(auth('sanctum')->user()->status) != null) {
            if (count(json_decode(auth('sanctum')->user()->status)) > 0) {
                return $next($request);
            }
        }
    }
}
