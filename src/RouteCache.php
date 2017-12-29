<?php

namespace RouteCache;

use Closure;
use Illuminate\Support\Facades\Cache;

class CachedRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $lifetime)
    {
        $cacheKey = $this->getCacheKey($request);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $next($request);

        Cache::put($cacheKey, $response, $lifetime/60);

        return $response;
    }

    protected function getCacheKey($request)
    {
        return $request->getMethod() . '/' .
            $request->path() . '/' .
            md5(json_encode($request->all()));
    }
}
