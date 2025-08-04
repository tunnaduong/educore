<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale', Cookie::get('locale', config('app.locale')));

        if (in_array($locale, ['vi', 'en', 'zh'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
            Cookie::queue('locale', $locale, 60 * 24 * 365);
        }

        return $next($request);
    }
}
