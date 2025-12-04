@extends('layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="text-center mb-4">
                    <h2 class="auth-title mb-1">إنشاء حساب جديد</h2>
                    <p class="auth-subtitle mb-0">سجّل الآن للاستفادة من العروض ومتابعة طلباتك بسهولة</p>
                </div>

                <div class="card shadow-sm border-0 auth-card">
                    <div class="card-header auth-card-header-register text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus ms-2"></i>
                            بيانات التسجيل
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

                        <form action="{{ route('register.post') }}" method="POST" class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user text-success ms-1"></i>
                                    الاسم الكامل *
                                </label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="الاسم كما في الهوية">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope text-success ms-1"></i>
                                    البريد الإلكتروني *
                                </label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="example@mail.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone text-success ms-1"></i>
                                    رقم الهاتف *
                                </label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tag text-success ms-1"></i>
                                    نوع الحساب *
                                </label>
                                <select name="type" class="form-select auth-select" required>
                                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>اختر نوع الحساب</option>
                                    <option value="retail" {{ old('type') == 'retail' ? 'selected' : '' }}>قطاعي</option>
                                    <option value="wholesale" {{ old('type') == 'wholesale' ? 'selected' : '' }}>جملة</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt text-success ms-1"></i>
                                    العنوان
                                </label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="المدينة - الحي - الشارع">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock text-success ms-1"></i>
                                        كلمة المرور *
                                    </label>
                                    <div class="input-group auth-input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control" required placeholder="********">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock text-success ms-1"></i>
                                        تأكيد كلمة المرور *
                                    </label>
                                    <div class="input-group auth-input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control" required placeholder="********">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn auth-btn-register w-100 py-2 mt-1">
                                <i class="fas fa-user-plus ms-2"></i> إنشاء الحساب
                            </button>
                        </form>

                        <hr class="my-4">

                        <p class="text-center mb-0 text-muted">
                            لديك حساب بالفعل؟
                            <a href="{{ route('login') }}" class="fw-bold auth-link-login">تسجيل الدخول</a>
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

    .auth-card-header-register {
        background: var(--gradient-success);
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
        border-color: var(--success-color);
        box-shadow: 0 0 0 3px rgba(0, 145, 118, 0.1);
    }

    .auth-input-group .input-group-text {
        background: #f8f9fc;
        border: none;
        border-right: 2px solid #e0e0e0;
        color: var(--primary-dark);
        padding: 12px 16px;
    }

    .auth-input-group:focus-within .input-group-text {
        border-right-color: var(--success-color);
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

    .auth-select {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 16px;
        padding-left: 40px;
        transition: all 0.3s ease;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: left 0.75rem center;
        background-size: 16px 12px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .auth-select:focus {
        border-color: var(--success-color);
        box-shadow: 0 0 0 3px rgba(0, 145, 118, 0.1);
        outline: none;
    }

    .auth-btn-register {
        background: var(--gradient-success);
        border: none;
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 10px;
    }

    .auth-btn-register:hover {
        background: #009176;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 145, 118, 0.3);
    }

    .auth-link-login {
        color: var(--primary-color);
        text-decoration: none;
    }

    .auth-link-login:hover {
        color: var(--primary-dark);
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
