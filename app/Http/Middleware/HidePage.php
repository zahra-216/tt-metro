<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends visitors to the home page when the admin has switched a whole page off
 * (Site > Show / hide sections). Used as ->middleware('page:about').
 */
class HidePage
{
    public function handle(Request $request, Closure $next, string $page): Response
    {
        if (! section_on('page.'.$page)) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
