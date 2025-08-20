<?php

namespace Illuminate\Cookie\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class AddQueuedCookiesToResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        foreach (Cookie::getQueuedCookies() as $cookie) {
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}
