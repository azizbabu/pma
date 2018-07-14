<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Boot
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
        $assets = url('assets');

        $themeAssets = $assets.'/themes/'.env('APP_THEME','simplex');
        $app_name = env('APP_NAME');

        view()->share(['assets' => $assets, 'themeAssets' => $themeAssets, 'app_name' => $app_name]);

        return $next($request);
    }
}
