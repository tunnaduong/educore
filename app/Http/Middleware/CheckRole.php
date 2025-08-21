<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Bạn cần đăng nhập để truy cập trang này');
        }

        if (! $user->is_active) {
            Auth::logout();
            abort(403, 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.');
        }

        $roleList = collect($roles)->flatMap(function ($r) {
            return explode(',', $r);
        })->map(fn ($r) => trim($r))->filter()->all();

        if (! in_array($user->role, $roleList)) {
            abort(403, 'Bạn không có quyền truy cập trang này');
        }

        return $next($request);
    }
}
