@extends('layouts.admin')

@section('title', 'تفاصيل طلب التسعير')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <div>
        <h1><i class="fas fa-tags"></i> طلب تسعير رقم {{ $pricingRequest->request_number }}</h1>
        <div class="text-muted">تاريخ الطلب: {{ $pricingRequest->created_at->format('Y-m-d H:i') }}</div>
    </div>
    <a href="{{ route('admin.pricing-requests.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات العميل</h5>
                <p class="mb-1"><strong>الاسم:</strong> {{ $pricingRequest->customer->name ?? 'غير معروف' }}</p>
                <p class="mb-1"><strong>الهاتف:</strong> {{ $pricingRequest->customer->phone ?? '-' }}</p>
                <p class="mb-1"><strong>البريد:</strong> {{ $pricingRequest->customer->email ?? '-' }}</p>
                <p class="mb-0"><strong>النوع:</strong> {{ $pricingRequest->customer->type === 'wholesale' ? 'جملة' : 'قطاعي' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات الطلب</h5>
                <p class="mb-1"><strong>رقم الطلب:</strong> {{ $pricingRequest->request_number }}</p>
                <p class="mb-1"><strong>عدد المنتجات:</strong> {{ $pricingRequest->items->count() }}</p>
                <p class="mb-1"><strong>الحالة:</strong>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-warning text-dark',
                            'priced' => 'bg-success',
                            'completed' => 'bg-info text-dark',
                            'cancelled' => 'bg-danger',
                        ];
                    @endphp
                    <span class="badge {{ $statusClasses[$pricingRequest->status] ?? 'bg-secondary' }}">
                        @switch($pricingRequest->status)
                            @case('pending') قيد الانتظار @break
                            @case('priced') تم التسعير @break
                            @case('completed') مكتمل @break
                            @case('cancelled') ملغي @break
                        @endswitch
                    </span>
                </p>
                @if($pricingRequest->status == 'priced')
                    <p class="mb-0"><strong>الإجمالي:</strong> {{ number_format($pricingRequest->getTotalPrice(), 2) }} ج.م</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">إدارة الحالة</h5>
                <form method="POST" action="{{ route('admin.pricing-requests.updateStatus', $pricingRequest->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">الحالة الحالية</label>
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="pending" {{ $pricingRequest->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="priced" {{ $pricingRequest->status === 'priced' ? 'selected' : '' }}>تم التسعير</option>
                            <option value="completed" {{ $pricingRequest->status === 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ $pricingRequest->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sync me-1"></i> تحديث الحالة
                    </button>
                </form>
                @if($pricingRequest->status == 'pending')
                    <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success w-100 mt-2">
                        <i class="fab fa-whatsapp"></i> إرسال عبر واتساب
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Pricing Form -->
@if($pricingRequest->status == 'pending')
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-dollar-sign"></i> تحديد الأسعار
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pricing-requests.updatePrices', $pricingRequest->id) }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم المنتج</th>
                                <th>الكمية</th>
                                <th>الوحدة</th>
                                <th>سعر الوحدة (ج.م)</th>
                                <th>الإجمالي</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pricingRequest->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit ?? '-' }}</td>
                                    <td>
                                        <input type="number" 
                                               name="prices[{{ $item->id }}]" 
                                               class="form-control price-input" 
                                               data-quantity="{{ $item->quantity }}"
                                               data-item-id="{{ $item->id }}"
                                               step="0.01" 
                                               min="0" 
                                               placeholder="0.00"
                                               value="{{ $item->price ?? '' }}">
                                    </td>
                                    <td class="total-cell" data-item-id="{{ $item->id }}">
                                        @if($item->price !== null)
                                            <strong>{{ number_format($item->getTotalPrice(), 2) }} ج.م</strong>
                                        @else
                                            <span class="text-muted">0.00 ج.م</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end"><strong>الإجمالي الكلي:</strong></td>
                                <td class="grand-total-cell">
                                    <strong>{{ number_format($pricingRequest->getTotalPrice(), 2) }} ج.م</strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-3">
                    <label class="form-label">
                        <i class="fas fa-comment-alt"></i> ملاحظات الإدمن
                    </label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="أي ملاحظات تريد إضافتها للعميل...">{{ $pricingRequest->admin_notes }}</textarea>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check"></i> حفظ الأسعار
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <!-- Display Prices -->
    <div class="table-card mt-4">
        <h3><i class="fas fa-boxes"></i> المنتجات والأسعار</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المنتج</th>
                        <th class="text-center">الكمية</th>
                        <th class="text-center">الوحدة</th>
                        <th class="text-center">سعر الوحدة</th>
                        <th class="text-center">الإجمالي</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricingRequest->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ $item->unit ?? '-' }}</td>
                            <td class="text-center">
                                @if($item->price !== null)
                                    {{ number_format($item->price, 2) }} ج.م
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->price !== null)
                                    {{ number_format($item->getTotalPrice(), 2) }} ج.م
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @if($pricingRequest->status == 'priced')
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>الإجمالي:</strong></td>
                            <td class="text-center"><strong>{{ number_format($pricingRequest->getTotalPrice(), 2) }} ج.م</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endif

@if($pricingRequest->notes)
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">ملاحظات العميل</h5>
            <p class="mb-0">{{ $pricingRequest->notes }}</p>
        </div>
    </div>
@endif

@if($pricingRequest->admin_notes)
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">ملاحظات الإدمن</h5>
            <p class="mb-0">{{ $pricingRequest->admin_notes }}</p>
        </div>
    </div>
@endif

@if($pricingRequest->status == 'pending')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceInputs = document.querySelectorAll('.price-input');
    const grandTotalCell = document.querySelector('.grand-total-cell');
    
    function calculateTotal() {
        let grandTotal = 0;
        
        priceInputs.forEach(input => {
            const price = parseFloat(input.value) || 0;
            const quantity = parseFloat(input.dataset.quantity) || 0;
            const itemId = input.dataset.itemId;
            const total = price * quantity;
            
            // تحديث الإجمالي لكل منتج
            const totalCell = document.querySelector(`.total-cell[data-item-id="${itemId}"]`);
            if (totalCell) {
                totalCell.innerHTML = `<strong>${total.toFixed(2)} ج.م</strong>`;
            }
            
            grandTotal += total;
        });
        
        // تحديث الإجمالي الكلي
        if (grandTotalCell) {
            grandTotalCell.innerHTML = `<strong>${grandTotal.toFixed(2)} ج.م</strong>`;
        }
    }
    
    // إضافة event listener لكل حقل سعر
    priceInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
        input.addEventListener('change', calculateTotal);
    });
    
    // حساب الإجمالي عند تحميل الصفحة
    calculateTotal();
});
</script>
@endif
@endsection

