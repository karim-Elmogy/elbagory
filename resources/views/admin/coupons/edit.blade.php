@extends('layouts.admin')

@section('title', 'تعديل الخصم')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tag"></i> تعديل الخصم</h2>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">كود الخصم *</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم الخصم *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $coupon->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $coupon->description) }}</textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">نوع الخصم *</label>
                        <select name="type" class="form-select" required>
                            <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                            <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ج.م)</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">قيمة الخصم *</label>
                        <input type="number" name="value" class="form-control" step="0.01" min="0" value="{{ old('value', $coupon->value) }}" required>
                        @error('value')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الحد الأدنى للطلب (ج.م)</label>
                        <input type="number" name="minimum_amount" class="form-control" step="0.01" min="0" value="{{ old('minimum_amount', $coupon->minimum_amount) }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الحد الأقصى للخصم (ج.م) - للنسبة المئوية فقط</label>
                        <input type="number" name="maximum_discount" class="form-control" step="0.01" min="0" value="{{ old('maximum_discount', $coupon->maximum_discount) }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">حد الاستخدام (عدد المرات)</label>
                        <input type="number" name="usage_limit" class="form-control" min="1" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                        <small class="text-muted">اتركه فارغاً للاستخدام غير المحدود</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">حد الاستخدام لكل مستخدم</label>
                        <input type="number" name="usage_limit_per_user" class="form-control" min="1" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">تاريخ البداية</label>
                        <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">تاريخ الانتهاء</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">نوع العملاء *</label>
                        <select name="customer_type" class="form-select" required>
                            <option value="all" {{ old('customer_type', $coupon->customer_type) === 'all' ? 'selected' : '' }}>الجميع</option>
                            <option value="retail" {{ old('customer_type', $coupon->customer_type) === 'retail' ? 'selected' : '' }}>قطاعي فقط</option>
                            <option value="wholesale" {{ old('customer_type', $coupon->customer_type) === 'wholesale' ? 'selected' : '' }}>جملة فقط</option>
                        </select>
                        @error('customer_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                تفعيل الخصم
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
