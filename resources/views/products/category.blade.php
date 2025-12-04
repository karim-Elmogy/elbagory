@extends('layouts.app')

@section('title', $category->name . ' - المنتجات')

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
                    <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        المنتجات
                    </a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-color); font-weight: 600;">
                    {{ $category->name }}
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="container animate-fade-in">
    <div class="row">
        <!-- Sidebar (اختياري: يمكن لاحقاً نسخ نفس فلتر الفئات إن أحببت) -->
        <div class="col-md-3 mb-4 animate-slide-in d-none d-md-block">
            <div class="card">
                <div class="card-header text-white" style="background: var(--gradient-primary);">
                    <h5 class="mb-0"><i class="fas fa-th-large"></i> الفئة الحالية</h5>
                </div>
                <div class="list-group list-group-flush">
                    <span class="list-group-item active" style="background-color: var(--primary-color); color: #fff;">
                        {{ $category->name }}
                    </span>
                    <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action">
                        عرض كل المنتجات
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Products -->
        <div class="col-md-9 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    <i class="fas fa-box"></i> {{ $category->name }}
                </h2>
                <span class="badge bg-primary" style="background: var(--gradient-primary) !important; padding: 8px 15px; font-size: 14px;">
                    {{ $products->total() }} منتج
                </span>
            </div>
            
            <div class="row">
                @forelse($products as $product)
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
                                    <span class="badge">{{ $product->category->name }}</span>
                                </div>
                                <div class="product-price">
                                    {{ number_format($product->retail_price, 2) }} ج.م
                                    @if($product->wholesale_price < $product->retail_price)
                                        <span class="old-price">{{ number_format($product->wholesale_price, 2) }} ج.م (جملة)</span>
                                    @endif
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
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h4>لا توجد منتجات في هذه الفئة</h4>
                            <p>يمكنك تصفح باقي المنتجات من <a href="{{ route('products.index') }}">هنا</a></p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


