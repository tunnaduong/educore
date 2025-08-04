<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1);
        if (in_array($locale, ['vi','en','zh'])) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        } elseif (session('locale')) {
            App::setLocale(session('locale'));
        }
        return $next($request);
    }
}
