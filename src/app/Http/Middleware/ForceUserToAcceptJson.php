<?php

namespace App\Http\Middleware;

use Closure;

class ForceUserToAcceptJson
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
        if (!$request->hasHeader('accept') || $request->header('accept') !== 'application/json'){
            $request->headers['accept'] = ['application/json'] ;
        }
        return $next($request);
    }
}
