<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLogin()
    {
        // إذا كان المستخدم مسجل دخول بالفعل، أرسله إلى الصفحة المناسبة
        if (Auth::check()) {
            $user = Auth::user();
            $user->load('role');
            
            if ($user->role && $user->role->slug === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        // التحقق من البيانات
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $remember = $request->boolean('remember');

        // محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // الحصول على المستخدم
            $user = Auth::user();
            
            // تحميل الدور
            $user->load('role');
            
            // التحقق من وجود URL مخصص للعودة إليه
            $intendedUrl = session()->pull('url.intended');
            
            // إذا كان admin، أرسله مباشرة إلى لوحة التحكم أو URL المخصص
            if ($user->role && $user->role->slug === 'admin') {
                return redirect($intendedUrl ?? route('admin.dashboard'))
                    ->with('success', 'مرحباً بك في لوحة التحكم');
            }
            
            // وإلا أرسله إلى الصفحة الرئيسية أو URL المخصص
            return redirect($intendedUrl ?? route('home'))
                ->with('success', 'مرحباً بك مرة أخرى');
        }

        // إذا فشل تسجيل الدخول
        return back()
            ->withErrors(['email' => 'بيانات الدخول غير صحيحة'])
            ->withInput($request->only('email'));
    }

    /**
     * عرض صفحة التسجيل
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        
        return view('auth.register');
    }

    /**
     * معالجة التسجيل
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|unique:customers,phone',
            'password' => ['required', 'confirmed', Password::defaults()],
            'type' => 'required|in:retail,wholesale',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'type.required' => 'نوع الحساب مطلوب',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // إنشاء حساب العميل
        Customer::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'type' => $validated['type'],
            'address' => $validated['address'] ?? null,
        ]);

        // تسجيل الدخول تلقائياً
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'تم إنشاء حسابك بنجاح!');
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }

}
