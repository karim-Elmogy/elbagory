@extends('layouts.app')

@section('title', 'حسابي')

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
                    حسابي
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Account Content -->
<div class="container my-5">
    <!-- Account Header -->
    <div class="account-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="account-title mb-2">
                    <i class="fas fa-user-circle text-primary"></i>
                    حسابي
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    إدارة معلومات حسابك الشخصي
                </p>
            </div>
            <div class="account-type-badge">
                <span class="badge bg-{{ $customer->type == 'wholesale' ? 'success' : 'primary' }}" style="font-size: 14px; padding: 10px 20px;">
                    <i class="fas fa-{{ $customer->type == 'wholesale' ? 'store' : 'user' }}"></i>
                    {{ $customer->type == 'wholesale' ? 'حساب جملة' : 'حساب قطاعي' }}
                </span>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Edit Form -->
        <div class="col-lg-8 mb-4">
            <div class="account-card">
                <div class="account-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit text-primary"></i>
                        تعديل البيانات
                    </h5>
                </div>
                <div class="account-card-body">
                    <form action="{{ route('customer.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user text-primary"></i>
                                    الاسم الكامل *
                                </label>
                                <input type="text" name="name" class="form-control account-input" value="{{ $customer->name }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone text-primary"></i>
                                    رقم الهاتف *
                                </label>
                                <input type="text" name="phone" class="form-control account-input" value="{{ $customer->phone }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-envelope text-primary"></i>
                                البريد الإلكتروني
                            </label>
                            <input type="email" name="email" class="form-control account-input" value="{{ $customer->email }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag text-primary"></i>
                                نوع الحساب *
                            </label>
                            <select name="type" class="form-select account-input" required>
                                <option value="retail" {{ $customer->type == 'retail' ? 'selected' : '' }}>قطاعي</option>
                                <option value="wholesale" {{ $customer->type == 'wholesale' ? 'selected' : '' }}>جملة</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                العنوان
                            </label>
                            <textarea name="address" class="form-control account-input" rows="3">{{ $customer->address }}</textarea>
                        </div>
                        
                        @if($customer->type == 'wholesale')
                            <div class="wholesale-section">
                                <div class="section-divider mb-4">
                                    <span>
                                        <i class="fas fa-building"></i>
                                        معلومات الشركة / المحل
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-store text-primary"></i>
                                        اسم الشركة / المحل *
                                    </label>
                                    <input type="text" name="company_name" class="form-control account-input" value="{{ $customer->company_name }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-file-invoice text-primary"></i>
                                        الرقم الضريبي
                                    </label>
                                    <input type="text" name="tax_number" class="form-control account-input" value="{{ $customer->tax_number }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-map-marked-alt text-primary"></i>
                                        العنوان التفصيلي
                                    </label>
                                    <textarea name="detailed_address" class="form-control account-input" rows="3">{{ $customer->detailed_address }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-credit-card text-primary"></i>
                                        طريقة الدفع المفضلة
                                    </label>
                                    <select name="preferred_payment_method" class="form-select account-input">
                                        <option value="cash" {{ $customer->preferred_payment_method == 'cash' ? 'selected' : '' }}>نقدي</option>
                                        <option value="bank_transfer" {{ $customer->preferred_payment_method == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                        <option value="credit" {{ $customer->preferred_payment_method == 'credit' ? 'selected' : '' }}>آجل</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        
                        <div class="account-form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent Orders -->
            <div class="account-sidebar-card mb-4">
                <div class="account-sidebar-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-bag text-info"></i>
                        آخر الطلبات
                    </h5>
                </div>
                <div class="account-sidebar-body">
                    @if($orders->count() > 0)
                        <div class="recent-orders-list">
                            @foreach($orders as $order)
                                <a href="{{ route('orders.show', $order->id) }}" class="recent-order-item">
                                    <div class="recent-order-header">
                                        <span class="recent-order-number">
                                            <i class="fas fa-receipt"></i>
                                            {{ $order->order_number }}
                                        </span>
                                        <span class="recent-order-total">{{ number_format($order->total, 2) }} ج.م</span>
                                    </div>
                                    <div class="recent-order-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100 mt-3">
                            <i class="fas fa-list"></i>
                            عرض جميع الطلبات
                        </a>
                    @else
                        <div class="empty-sidebar">
                            <i class="fas fa-shopping-bag"></i>
                            <p class="text-muted mb-0">لا توجد طلبات حالياً</p>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($customer->type == 'wholesale')
                <!-- Pricing Requests -->
                <div class="account-sidebar-card mb-4">
                    <div class="account-sidebar-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tags text-warning"></i>
                            طلبات التسعير
                        </h5>
                    </div>
                    <div class="account-sidebar-body">
                        @php
                            $pricingRequestsCount = \App\Models\PricingRequest::where('customer_id', $customer->id)->count();
                            $pendingPricingRequests = \App\Models\PricingRequest::where('customer_id', $customer->id)->where('status', 'pending')->count();
                        @endphp
                        <div class="quick-stats">
                            <div class="quick-stat-item">
                                <i class="fas fa-tags text-warning"></i>
                                <div>
                                    <span class="stat-label">إجمالي الطلبات</span>
                                    <span class="stat-value">{{ $pricingRequestsCount }}</span>
                                </div>
                            </div>
                            @if($pendingPricingRequests > 0)
                                <div class="quick-stat-item">
                                    <i class="fas fa-clock text-info"></i>
                                    <div>
                                        <span class="stat-label">قيد الانتظار</span>
                                        <span class="stat-value">{{ $pendingPricingRequests }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('pricing-requests.index') }}" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-list"></i> عرض الطلبات
                            </a>
                            <a href="{{ route('pricing-requests.create') }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-plus"></i> طلب جديد
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Quick Stats -->
            <div class="account-sidebar-card">
                <div class="account-sidebar-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-success"></i>
                        إحصائيات سريعة
                    </h5>
                </div>
                <div class="account-sidebar-body">
                    <div class="quick-stats">
                        <div class="quick-stat-item">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <div>
                                <span class="stat-label">إجمالي الطلبات</span>
                                <span class="stat-value">{{ $orders->count() }}</span>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <i class="fas fa-heart text-danger"></i>
                            <div>
                                <span class="stat-label">المفضلة</span>
                                <span class="stat-value">
                                    {{ \App\Models\Wishlist::where('user_id', auth()->id())->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
