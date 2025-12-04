@extends('layouts.admin')

@section('title', 'الإعدادات')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-cog"></i> الإعدادات</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    
    <!-- إعدادات الضريبة -->
    <div class="table-card mb-4">
        <h3><i class="fas fa-receipt"></i> إعدادات الضريبة</h3>
        
        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-check form-switch">
                    @php
                        $taxEnabled = \App\Models\Setting::get('tax_enabled', false);
                    @endphp
                    <input class="form-check-input" type="checkbox" name="settings[tax_enabled]" 
                           id="tax_enabled" value="1" {{ $taxEnabled ? 'checked' : '' }}>
                    <label class="form-check-label" for="tax_enabled">
                        <strong>تفعيل الضريبة</strong>
                    </label>
                    <small class="form-text text-muted d-block">
                        تفعيل أو إلغاء تفعيل الضريبة على المبيعات
                    </small>
                </div>
            </div>
            
            <div class="col-md-6">
                <label for="tax_rate" class="form-label">نسبة الضريبة (%)</label>
                <input type="number" 
                       class="form-control" 
                       id="tax_rate" 
                       name="settings[tax_rate]" 
                       value="{{ \App\Models\Setting::get('tax_rate', 14) }}" 
                       min="0" 
                       max="100" 
                       step="0.01"
                       required>
                <small class="form-text text-muted">
                    أدخل نسبة الضريبة كنسبة مئوية (مثال: 14 لضريبة 14%)
                </small>
            </div>
        </div>
    </div>
    
    <!-- الإعدادات العامة -->
    <div class="table-card mb-4">
        <h3><i class="fas fa-store"></i> الإعدادات العامة</h3>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label for="store_name" class="form-label">اسم المتجر</label>
                <input type="text" 
                       class="form-control" 
                       id="store_name" 
                       name="settings[store_name]" 
                       value="{{ \App\Models\Setting::get('store_name', 'متجر إلكتروني') }}" 
                       required>
            </div>
            
            <div class="col-md-6">
                <label for="store_email" class="form-label">البريد الإلكتروني</label>
                <input type="email" 
                       class="form-control" 
                       id="store_email" 
                       name="settings[store_email]" 
                       value="{{ \App\Models\Setting::get('store_email', 'info@example.com') }}" 
                       required>
            </div>
            
            <div class="col-md-6">
                <label for="store_phone" class="form-label">رقم الهاتف</label>
                <input type="text" 
                       class="form-control" 
                       id="store_phone" 
                       name="settings[store_phone]" 
                       value="{{ \App\Models\Setting::get('store_phone', '(+20) 123 456 7890') }}" 
                       required>
            </div>
            
            <div class="col-md-6">
                <label for="whatsapp_number" class="form-label">
                    <i class="fab fa-whatsapp text-success"></i> رقم الواتساب
                </label>
                <input type="text" 
                       class="form-control" 
                       id="whatsapp_number" 
                       name="settings[whatsapp_number]" 
                       value="{{ \App\Models\Setting::get('whatsapp_number', '201234567890') }}" 
                       placeholder="201234567890"
                       required>
                <small class="form-text text-muted">
                    أدخل رقم الواتساب بدون رموز (مثال: 201234567890)
                </small>
            </div>
            
            <div class="col-md-12">
                <label for="store_logo" class="form-label">لوجو المتجر</label>
                <div class="mb-2">
                    @php
                        $storeLogo = \App\Models\Setting::get('store_logo', 'logo.png');
                    @endphp
                    @if(file_exists(public_path($storeLogo)) || file_exists(public_path('logo.png')))
                        <img src="{{ asset(file_exists(public_path($storeLogo)) ? $storeLogo : 'logo.png') }}" 
                             alt="لوجو المتجر" 
                             style="max-height: 100px; max-width: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 5px; margin-bottom: 10px;">
                    @endif
                </div>
                <input type="text" 
                       class="form-control" 
                       id="store_logo" 
                       name="settings[store_logo]" 
                       value="{{ $storeLogo }}" 
                       placeholder="logo.png"
                       required>
                <small class="form-text text-muted">
                    اسم ملف اللوجو في مجلد public (مثال: logo.png). تأكد من وجود الملف في مجلد public.
                </small>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-times"></i> إلغاء
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> حفظ الإعدادات
        </button>
    </div>
</form>

<script>
    // إخفاء/إظهار حقل نسبة الضريبة حسب حالة التفعيل
    document.addEventListener('DOMContentLoaded', function() {
        const taxEnabled = document.getElementById('tax_enabled');
        const taxRateField = document.getElementById('tax_rate').closest('.col-md-6');
        const form = document.querySelector('form');
        
        function toggleTaxRate() {
            if (taxEnabled.checked) {
                taxRateField.style.display = 'block';
            } else {
                taxRateField.style.display = 'none';
            }
        }
        
        // إضافة hidden input قبل الإرسال إذا كان checkbox غير محدد
        form.addEventListener('submit', function(e) {
            if (!taxEnabled.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'settings[tax_enabled]';
                hiddenInput.value = '0';
                form.appendChild(hiddenInput);
            }
        });
        
        taxEnabled.addEventListener('change', toggleTaxRate);
        toggleTaxRate(); // تشغيل عند تحميل الصفحة
    });
</script>
@endsection

