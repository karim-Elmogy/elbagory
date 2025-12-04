@extends('layouts.admin')

@section('title', 'إنشاء فاتورة جملة')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-file-invoice"></i> إنشاء فاتورة جملة</h1>
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<form method="POST" action="{{ route('admin.invoices.store') }}" id="invoiceForm">
    @csrf
    
    <div class="row g-4">
        <!-- معلومات الفاتورة -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">معلومات الفاتورة</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">العميل <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">اختر العميل</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="cash">نقدي</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="credit">آجل</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="credit_days_group" style="display: none;">
                        <label class="form-label">أيام الآجل</label>
                        <input type="number" name="credit_days" class="form-control" min="0" value="0">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الضريبة</label>
                        <input type="number" name="tax" id="tax" class="form-control" step="0.01" min="0" placeholder="اتركه فارغاً لحساب 14% تلقائياً">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- عناصر الفاتورة -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">عناصر الفاتورة</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addItem">
                            <i class="fas fa-plus"></i> إضافة منتج
                        </button>
                    </div>
                    
                    <div id="itemsContainer">
                        <!-- سيتم إضافة العناصر هنا ديناميكياً -->
                    </div>
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row text-end">
                            <div class="col-md-6 offset-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>المجموع الفرعي:</span>
                                    <strong id="subtotal">0.00</strong> ج.م
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>إجمالي الخصومات:</span>
                                    <strong id="totalDiscount">0.00</strong> ج.م
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>بعد الخصم:</span>
                                    <strong id="totalAfterDiscount">0.00</strong> ج.م
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>الضريبة:</span>
                                    <strong id="taxAmount">0.00</strong> ج.م
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fs-5">الإجمالي:</span>
                                    <strong class="fs-5 text-primary" id="total">0.00</strong> ج.م
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> حفظ الفاتورة
        </button>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </div>
</form>

@push('scripts')
<script>
let itemIndex = 0;
const products = @json($products);

// إظهار/إخفاء حقل أيام الآجل
document.getElementById('payment_method').addEventListener('change', function() {
    document.getElementById('credit_days_group').style.display = this.value === 'credit' ? 'block' : 'none';
});

// إضافة منتج جديد
document.getElementById('addItem').addEventListener('click', function() {
    addItemRow();
});

function addItemRow(item = null) {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'item-row mb-3 p-3 border rounded';
    row.dataset.index = itemIndex;
    
    row.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">المنتج</label>
                <select name="items[${itemIndex}][product_id]" class="form-select product-select" required>
                    <option value="">اختر المنتج</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.wholesale_price}" ${item && item.product_id == p.id ? 'selected' : ''}>${p.name} - ${p.wholesale_price} ج.م</option>`).join('')}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">الكمية</label>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" min="1" value="${item ? item.quantity : 1}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">سعر الوحدة</label>
                <input type="number" name="items[${itemIndex}][unit_price]" class="form-control unit-price" step="0.01" min="0" value="${item ? item.unit_price : ''}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small">خصم %</label>
                <input type="number" name="items[${itemIndex}][discount_percentage]" class="form-control discount" step="0.01" min="0" max="100" value="${item ? item.discount_percentage : 0}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">الإجمالي</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control item-total" readonly value="0.00">
                    <button type="button" class="btn btn-sm btn-danger remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(row);
    
    // إضافة event listeners
    const productSelect = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity');
    const unitPriceInput = row.querySelector('.unit-price');
    const discountInput = row.querySelector('.discount');
    const removeBtn = row.querySelector('.remove-item');
    
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.price) {
            unitPriceInput.value = selectedOption.dataset.price;
            calculateItemTotal(row);
        }
    });
    
    [quantityInput, unitPriceInput, discountInput].forEach(input => {
        input.addEventListener('input', () => calculateItemTotal(row));
    });
    
    removeBtn.addEventListener('click', function() {
        row.remove();
        calculateTotals();
    });
    
    itemIndex++;
}

function calculateItemTotal(row) {
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const discount = parseFloat(row.querySelector('.discount').value) || 0;
    
    const itemTotal = quantity * unitPrice;
    const discountAmount = itemTotal * (discount / 100);
    const total = itemTotal - discountAmount;
    
    row.querySelector('.item-total').value = total.toFixed(2);
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const discount = parseFloat(row.querySelector('.discount').value) || 0;
        
        const itemTotal = quantity * unitPrice;
        const discountAmount = itemTotal * (discount / 100);
        
        subtotal += itemTotal;
        totalDiscount += discountAmount;
    });
    
    const totalAfterDiscount = subtotal - totalDiscount;
    const taxInput = document.getElementById('tax');
    const tax = taxInput.value ? parseFloat(taxInput.value) : (totalAfterDiscount * 0.14);
    const total = totalAfterDiscount + tax;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('totalDiscount').textContent = totalDiscount.toFixed(2);
    document.getElementById('totalAfterDiscount').textContent = totalAfterDiscount.toFixed(2);
    document.getElementById('taxAmount').textContent = tax.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

// إضافة منتج افتراضي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    addItemRow();
    
    // تحديث الضريبة عند تغييرها
    document.getElementById('tax').addEventListener('input', calculateTotals);
});

// التحقق من وجود عناصر قبل الإرسال
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    if (document.querySelectorAll('.item-row').length === 0) {
        e.preventDefault();
        alert('يجب إضافة منتج واحد على الأقل');
        return false;
    }
});
</script>
@endpush

@endsection

