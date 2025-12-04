@extends('layouts.admin')

@section('title', 'تعديل عميل')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-edit"></i> تعديل عميل: {{ $customer->name }}</h1>
    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- المعلومات الأساسية -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">المعلومات الأساسية</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الهاتف <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">النوع <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="retail" {{ old('type', $customer->type) === 'retail' ? 'selected' : '' }}>قطاعي</option>
                            <option value="wholesale" {{ old('type', $customer->type) === 'wholesale' ? 'selected' : '' }}>جملة</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="suspended" {{ old('status', $customer->status) === 'suspended' ? 'selected' : '' }}>موقوف</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- معلومات إضافية -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">معلومات إضافية</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">اسم الشركة</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الرقم الضريبي</label>
                        <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number', $customer->tax_number) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">العنوان التفصيلي</label>
                        <textarea name="detailed_address" class="form-control" rows="2">{{ old('detailed_address', $customer->detailed_address) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع المفضلة</label>
                        <select name="preferred_payment_method" class="form-select">
                            <option value="">اختر طريقة الدفع</option>
                            <option value="cash" {{ old('preferred_payment_method', $customer->preferred_payment_method) === 'cash' ? 'selected' : '' }}>نقدي</option>
                            <option value="bank_transfer" {{ old('preferred_payment_method', $customer->preferred_payment_method) === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="credit" {{ old('preferred_payment_method', $customer->preferred_payment_method) === 'credit' ? 'selected' : '' }}>آجل</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الحد الائتماني</label>
                        <input type="number" name="credit_limit" class="form-control" step="0.01" min="0" value="{{ old('credit_limit', $customer->credit_limit) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </div>
</form>
@endsection

