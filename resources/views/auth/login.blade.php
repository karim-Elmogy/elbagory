@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="text-center mb-4">
                    <h2 class="auth-title mb-1">مرحباً بك من جديد</h2>
                    <p class="auth-subtitle mb-0">سجّل دخولك للوصول إلى حسابك ومتابعة طلباتك</p>
                </div>

                <div class="card shadow-sm border-0 auth-card">
                    <div class="card-header auth-card-header-login text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-sign-in-alt ms-2"></i>
                            تسجيل الدخول
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle ms-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle ms-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST" class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope text-primary ms-1"></i>
                                    البريد الإلكتروني *
                                </label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="example@mail.com">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock text-primary ms-1"></i>
                                    كلمة المرور *
                                </label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" required placeholder="********">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                                    <label class="form-check-label" for="remember_me">تذكرني</label>
                                </div>
                                {{-- يمكن إضافة رابط "نسيت كلمة المرور؟" لاحقاً --}}
                            </div>
                            <button type="submit" class="btn auth-btn-login w-100 py-2">
                                <i class="fas fa-sign-in-alt ms-2"></i> تسجيل الدخول
                            </button>
                        </form>

                        <hr class="my-4">

                        <p class="text-center mb-0 text-muted">
                            لا تملك حساباً؟
                            <a href="{{ route('register') }}" class="fw-bold auth-link-register">إنشاء حساب جديد</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .auth-page {
        min-height: calc(100vh - 120px);
        background: radial-gradient(circle at top, #ffffff 0, #f7f7fa 55%, #eceff4 100%);
    }

    .auth-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .auth-card-header-login {
        background: var(--gradient-primary);
        border-bottom: none;
    }

    .auth-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-dark);
    }

    .auth-subtitle {
        font-size: 14px;
        color: #6c757d;
    }

    .auth-input-group {
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .auth-input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(64, 69, 83, 0.1);
    }

    .auth-input-group .input-group-text {
        background: #f8f9fc;
        border: none;
        border-right: 2px solid #e0e0e0;
        color: var(--primary-dark);
        padding: 12px 16px;
    }

    .auth-input-group:focus-within .input-group-text {
        border-right-color: var(--primary-color);
    }

    .auth-input-group .form-control {
        border: none;
        padding: 12px 16px;
        background: white;
    }

    .auth-input-group .form-control:focus {
        border: none;
        box-shadow: none;
        outline: none;
    }

    .auth-btn-login {
        background: var(--gradient-primary);
        border: none;
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 10px;
    }

    .auth-btn-login:hover {
        background: var(--primary-dark);
    }

    .auth-link-register {
        color: var(--success-color);
        text-decoration: none;
    }

    .auth-link-register:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .auth-title {
            font-size: 22px;
        }

        .auth-card {
            border-radius: 12px;
        }
    }
</style>
@endpush
