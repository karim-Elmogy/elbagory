@extends('layouts.app')

@section('title', 'سلة الشراء')

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
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-color); font-weight: 600;">
                    سلة الشراء
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Cart Content -->
<div class="container my-5">
    <!-- Cart Header -->
    <div class="cart-page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="cart-page-title mb-2">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    سلة الشراء
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    مراجعة المنتجات قبل إتمام الطلب
                </p>
            </div>
            @if(count($items) > 0)
                <span class="badge bg-primary" style="font-size: 14px; padding: 8px 15px;">
                    <i class="fas fa-box"></i>
                    {{ count($items) }} منتج في السلة
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($items) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="cart-item-row">
                                    <td data-label="المنتج">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item['product']->main_image ? asset('storage/' . $item['product']->main_image) : asset('images/no-product.svg') }}" 
                                                 alt="{{ $item['product']->name }}"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';" 
                                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-left: 15px;">
                                            <div class="cart-product-info">
                                                <h6 class="mb-1">{{ $item['product']->name }}</h6>
                                                <small class="text-muted">{{ $item['product']->category->name ?? 'غير مصنف' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="السعر">
                                        {{ number_format($item['price'], 2) }} ج.م
                                    </td>
                                    <td data-label="الكمية">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <div class="cart-quantity-wrapper">
                                                <input type="number" 
                                                       name="quantity" 
                                                       class="form-control cart-quantity-input" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       max="{{ $item['product']->stock_quantity }}"
                                                       style="width: 80px; text-align: center;">
                                                <button type="submit" class="btn btn-primary cart-quantity-btn">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td data-label="الإجمالي">
                                        <strong>{{ number_format($item['total'], 2) }} ج.م</strong>
                                    </td>
                                    <td data-label="إجراء">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف من السلة">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-right"></i> متابعة التسوق
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('هل أنت متأكد من تفريغ السلة؟')">
                            <i class="fas fa-trash"></i> تفريغ السلة
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="cart-summary-card">
                    <div class="cart-summary-header">
                        <h5 class="mb-0">ملخص الطلب</h5>
                    </div>
                    <div class="cart-summary-body">
                        <!-- كود الخصم -->
                        <div class="mb-3">
                            @if(isset($coupon) && $coupon)
                                <div class="alert alert-success mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-tag"></i> <strong>{{ $coupon->code }}</strong>
                                            <br>
                                            <small>خصم: {{ number_format($couponDiscount ?? 0, 2) }} ج.م</small>
                                        </div>
                                        <form action="{{ route('cart.removeCoupon') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('cart.applyCoupon') }}" method="POST" class="d-flex gap-2 mb-2 coupon-form">
                                    @csrf
                                    <input type="text" name="coupon_code" class="form-control" placeholder="كود الخصم" required>
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-tag"></i>
                                    </button>
                                </form>
                                @if(isset($couponMessage) && $couponMessage)
                                    <div class="alert alert-danger alert-sm">
                                        {{ $couponMessage }}
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        <div class="cart-summary-row">
                            <span>المجموع الفرعي:</span>
                            <span>{{ number_format($subtotal, 2) }} ج.م</span>
                        </div>
                        
                        @if(isset($coupon) && $coupon && isset($couponDiscount) && $couponDiscount > 0)
                        <div class="cart-summary-row text-success">
                            <span><i class="fas fa-tag"></i> خصم:</span>
                            <span>- {{ number_format($couponDiscount, 2) }} ج.م</span>
                        </div>
                        @endif
                        
                        @if(isset($tax) && $tax > 0)
                        <div class="cart-summary-row">
                            <span>الضريبة ({{ number_format($taxRate ?? 0, 2) }}%):</span>
                            <span>{{ number_format($tax, 2) }} ج.م</span>
                        </div>
                        @endif
                        
                        <div class="cart-summary-total mb-3">
                            <span>المجموع الكلي:</span>
                            <span>{{ number_format($total, 2) }} ج.م</span>
                        </div>
                        @auth
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="fas fa-check"></i> إتمام الطلب
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-sign-in-alt"></i> تسجيل الدخول لإتمام الطلب
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="empty-cart-title">السلة فارغة</h3>
            <p class="empty-cart-text">لا توجد منتجات في السلة حالياً</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> ابدأ التسوق
            </a>
        </div>
    @endif
</div>
@endsection
