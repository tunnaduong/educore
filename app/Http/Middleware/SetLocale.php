<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra locale từ session trước
        $sessionLocale = session('locale');
        
        if ($sessionLocale && in_array($sessionLocale, ['vi', 'en', 'zh'])) {
            App::setLocale($sessionLocale);
        } else {
            // Fallback về tiếng Việt nếu không có locale trong session
            App::setLocale('vi');
            session(['locale' => 'vi']);
        }
        
        return $next($request);
    }
}
