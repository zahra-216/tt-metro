<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * www.ttmetrocampus.com and ttmetrocampus.com serve the same site, which Google may count as two copies.
 * Page visits (GET) on the www address are sent permanently (301) to the address without www.
 * Not used on a local computer.
 */
class RedirectWww
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (! app()->isLocal() && $request->isMethod('GET') && str_starts_with($host, 'www.')) {
            return redirect()->to('https://'.substr($host, 4).$request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
