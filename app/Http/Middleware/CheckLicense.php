<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    /**
     * Handle an incoming request.
     * Kiểm tra license của user trước khi cho phép truy cập
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bỏ qua middleware cho route webhook của SEPay
        if ($request->is('api/sepay/webhook')) {
            return $next($request);
        }

        $user = $request->user();

        // Nếu chưa đăng nhập, middleware auth sẽ xử lý
        if (! $user) {
            return $next($request);
        }

        // Kiểm tra user có license đang active không
        if (! $user->hasActiveLicense()) {  // Redirect đến trang upgrade
            return redirect()
                ->to(route('upgrade.index'))
                ->with('error', 'Bạn cần có license hợp lệ để truy cập tính năng này. Vui lòng nâng cấp gói của bạn.');
        }

        return $next($request);
    }
}
