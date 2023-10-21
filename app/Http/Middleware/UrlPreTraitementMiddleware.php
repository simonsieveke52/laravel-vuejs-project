<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UrlPreTraitementMiddleware
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
        /**
         * Check if current request has gclid, we need to save it on session
         * This is stored with customer orders after checking out.
         */
        if ($request->has('gclid')) {
            session(['gclid' => $request->gclid]);
        }

        // if (! $this->isSecure($request)) {
        //     $query = $request->getQueryString();
        //     $question = trim($query) !== '' ? '?' : '';
        //     return redirect()->secure($request->path() . $question . $query);
        // }

        return $next($request);
    }

    /**
     * check whether x-forward-proto is provided by the reverse-proxy
     *
     * @param Request $request
     * @return bool
     */
    public function isSecure(Request $request)
    {
        return strtolower($request->headers->get('x-forwarded-proto')) === 'https'
            ? true
            : $request->secure();
    }
}
