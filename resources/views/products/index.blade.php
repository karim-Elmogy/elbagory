@extends('layouts.app')

@section('title', 'المنتجات')

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
                    المنتجات
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="container my-5">
    <!-- Products Header -->
    <div class="products-page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="products-page-title mb-2">
                    <i class="fas fa-box text-primary"></i>
                    المنتجات
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    تصفح جميع منتجاتنا المتاحة
                </p>
            </div>
            <div class="products-count-badge">
                <span class="badge bg-primary" style="font-size: 14px; padding: 10px 20px;">
                    <i class="fas fa-shopping-bag"></i>
                    {{ $products->total() }} منتج
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="products-sidebar">
                <!-- Categories Filter -->
                <div class="sidebar-card mb-4">
                    <div class="sidebar-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-th-large text-primary"></i>
                            الفئات
                        </h5>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="categories-list">
                            <a href="{{ route('products.index') }}" 
                               class="category-item {{ !request('category') ? 'active' : '' }}">
                                <i class="fas fa-th"></i>
                                <span>الكل</span>
                                @if(!request('category'))
                                    <i class="fas fa-check ms-auto"></i>
                                @endif
                            </a>
                            @foreach($categories ?? [] as $category)
                                <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                                   class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                                    <i class="fas fa-folder"></i>
                                    <span>{{ $category->name }}</span>
                                    @if(request('category') == $category->id)
                                        <i class="fas fa-check ms-auto"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products -->
        <div class="col-lg-9">
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    @if($product->is_featured)
                                        <span class="product-badge">مميز</span>
                                    @endif
                                    <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                                        <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/no-product.svg') }}" 
                                             alt="{{ $product->name }}" class="product-image"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                                    </a>
                                </div>
                                <div class="product-info">
                                    <h5 class="product-title">
                                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <div class="product-category">
                                        <span class="badge">{{ $product->category->name ?? 'غير مصنف' }}</span>
                                    </div>
                                    <div class="product-price">
                                        @auth
                                            @php
                                                $customerType = auth()->user()->customers()->first()?->type ?? 'retail';
                                                $price = $product->getPriceForCustomer($customerType);
                                            @endphp
                                            {{ number_format($price, 2) }} ج.م
                                            @if($customerType == 'retail' && $product->wholesale_price < $product->retail_price)
                                                <span class="old-price">{{ number_format($product->wholesale_price, 2) }} ج.م (جملة)</span>
                                            @endif
                                        @else
                                            {{ number_format($product->retail_price, 2) }} ج.م
                                            @if($product->wholesale_price < $product->retail_price)
                                                <span class="old-price">{{ number_format($product->wholesale_price, 2) }} ج.م (جملة)</span>
                                            @endif
                                        @endauth
                                    </div>
                                    @if($product->isLowStock())
                                        <div class="product-stock-alert">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>مخزون منخفض</span>
                                        </div>
                                    @endif
                                    <div class="product-actions">
                                        @auth
                                            @php
                                                $isInWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                                    ->where('product_id', $product->id)
                                                    ->exists();
                                            @endphp
                                            <a href="{{ route('cart.add', $product->slug ?? $product->id) }}" class="btn btn-add-cart">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>إضافة للسلة</span>
                                            </a>
                                            <button type="button" class="btn btn-wishlist wishlist-btn" data-product-slug="{{ $product->slug ?? $product->id }}" title="المفضلة">
                                                <i class="fas fa-heart {{ $isInWishlist ? 'text-danger' : 'text-muted' }}"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('cart.add', $product->slug ?? $product->id) }}" class="btn btn-add-cart">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>إضافة للسلة</span>
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-wishlist" title="تسجيل الدخول للمفضلة">
                                                <i class="fas fa-heart text-muted"></i>
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="empty-products">
                    <div class="empty-products-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="empty-products-title">لا توجد منتجات متاحة</h3>
                    <p class="empty-products-text">لم يتم العثور على منتجات تطابق البحث</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-redo"></i>
                        عرض جميع المنتجات
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
