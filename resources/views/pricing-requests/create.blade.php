@extends('layouts.app')

@section('title', 'طلب تسعير جديد')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 align-items-center" style="background: transparent;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        الرئيسية
                    </a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pricing-requests.index') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        طلبات التسعير
                    </a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-color); font-weight: 600;">
                    طلب تسعير جديد
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Create Pricing Request Content -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tags"></i> طلب تسعير جديد
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('pricing-requests.store') }}" method="POST" id="pricingRequestForm">
                        @csrf
                        
                        <!-- Products Section -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-box text-primary"></i> المنتجات
                            </h5>
                            <div id="itemsContainer">
                                <div class="item-row mb-3 p-3 border rounded" data-index="0">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">اسم المنتج *</label>
                                            <input type="text" name="items[0][product_name]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">الكمية *</label>
                                            <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">الوحدة</label>
                                            <select name="items[0][unit]" class="form-select">
                                                <option value="">اختر الوحدة</option>
                                                <option value="قطعة">قطعة</option>
                                                <option value="كرتونة">كرتونة</option>
                                                <option value="كيس">كيس</option>
                                                <option value="علبة">علبة</option>
                                                <option value="زجاجة">زجاجة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="جرام">جرام</option>
                                                <option value="لتر">لتر</option>
                                                <option value="متر">متر</option>
                                                <option value="طرد">طرد</option>
                                                <option value="وحدة">وحدة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">ملاحظات</label>
                                            <input type="text" name="items[0][notes]" class="form-control" placeholder="اختياري">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="addItem">
                                <i class="fas fa-plus"></i> إضافة منتج آخر
                            </button>
                        </div>

                        <!-- Notes Section -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-sticky-note text-primary"></i> ملاحظات عامة
                            </label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية تريد إضافتها..."></textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between gap-3">
                            <a href="{{ route('pricing-requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> إرسال طلب التسعير
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItem');

    addItemBtn.addEventListener('click', function() {
        const newItem = document.querySelector('.item-row').cloneNode(true);
        newItem.setAttribute('data-index', itemIndex);
        newItem.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[0\]/, `[${itemIndex}]`));
                if (input.tagName === 'INPUT') {
                    input.value = input.type === 'number' ? '1' : '';
                } else if (input.tagName === 'SELECT') {
                    input.value = '';
                }
            }
        });
        newItem.querySelector('.remove-item').style.display = 'block';
        itemsContainer.appendChild(newItem);
        itemIndex++;
    });

    itemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const itemRows = itemsContainer.querySelectorAll('.item-row');
            if (itemRows.length > 1) {
                e.target.closest('.item-row').remove();
            }
        }
    });
});
</script>
@endsection

