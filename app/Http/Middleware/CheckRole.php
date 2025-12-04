<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // التحقق من تسجيل الدخول - يجب أن يكون المستخدم مسجل دخول بالفعل
        // لأن هذا middleware يعمل بعد auth middleware
        if (!auth()->check()) {
            // هذا لا يجب أن يحدث أبداً إذا كان auth middleware يعمل بشكل صحيح
            // لكن للاحتياط، نعيد التوجيه إلى صفحة تسجيل الدخول
            if (!$request->expectsJson()) {
                session()->put('url.intended', $request->fullUrl());
                return redirect()->route('login')
                    ->with('error', 'يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة');
            }
            
            return response()->json([
                'message' => 'غير مصرح. يرجى تسجيل الدخول أولاً'
            ], 401);
        }

        $user = auth()->user();
        
        // تحميل العلاقة role إذا لم تكن محملة
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        
        // التحقق من وجود دور للمستخدم
        if (!$user->role) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'ليس لديك صلاحيات للوصول إلى هذه الصفحة'
                ], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'ليس لديك صلاحيات للوصول إلى هذه الصفحة');
        }
        
        // التحقق من أن الدور مطابق
        if ($user->role->slug !== $role) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'غير مصرح لك بالوصول إلى هذه الصفحة. الدور المطلوب: ' . $role
                ], 403);
            }
            
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة. الدور المطلوب: ' . $role);
        }

        return $next($request);
    }
}
