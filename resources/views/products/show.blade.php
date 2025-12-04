@extends('layouts.app')

@section('title', $product->name)

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
                    {{ $product->name }}
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Details -->
<div class="container">
    <div class="product-detail-card mb-5">
        <div class="row g-4 align-items-start">
            <div class="col-md-6">
                <div class="product-image-wrapper mb-3">
                    @if($product->is_featured)
                        <span class="product-badge">مميز</span>
                    @endif
                    <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/no-product.svg') }}" 
                         alt="{{ $product->name }}" class="product-image"
                         onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="product-detail-title">{{ $product->name }}</h1>
                <div class="product-detail-meta">
                    <span class="badge bg-primary">{{ $product->category->name }}</span>
                    <span class="badge bg-secondary">كود: {{ $product->code }}</span>
                    @if($product->barcode)
                        <span class="badge bg-light text-dark border">الباركود: {{ $product->barcode }}</span>
                    @endif
                </div>

                @auth
                    @php
                        $customerType = auth()->user()->customers()->first()?->type ?? 'retail';
                        $price = $product->getPriceForCustomer($customerType);
                    @endphp
                @endauth

                <div class="product-detail-price-box">
                    <div class="product-detail-price-main">
                        @auth
                            {{ number_format($price, 2) }} ج.م
                        @else
                            {{ number_format($product->retail_price, 2) }} ج.م
                        @endauth
                    </div>
                    @auth
                        @if($customerType == 'retail' && $product->wholesale_price < $product->retail_price)
                            <div class="product-detail-price-sub">
                                سعر الجملة: {{ number_format($product->wholesale_price, 2) }} ج.م
                            </div>
                        @endif
                    @endauth
                </div>

                @php
                    $isLowStock = $product->isLowStock();
                @endphp
                <div class="mb-2">
                    <span class="product-detail-stock-pill {{ $isLowStock ? 'low' : '' }}">
                        <i class="fas fa-box"></i>
                        @if($isLowStock)
                            مخزون منخفض ({{ $product->stock_quantity }} {{ $product->unit }})
                        @else
                            متوفر في المخزون ({{ $product->stock_quantity }} {{ $product->unit }})
                        @endif
                    </span>
                </div>

                <ul class="product-detail-meta-list">
                    <li><strong>الوحدة:</strong> {{ $product->unit }}</li>
                    @if($product->barcode)
                        <li><strong>الباركود:</strong> {{ $product->barcode }}</li>
                    @endif
                </ul>

                @if($product->description)
                    <div class="mb-3">
                        <h5 class="mb-2">الوصف</h5>
                        <p class="mb-0 text-muted">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="product-detail-actions mt-3">
                    @auth
                        @php
                            $isInWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                ->where('product_id', $product->id)
                                ->exists();
                        @endphp
                        <form action="{{ route('cart.add', $product->slug ?? $product->id) }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-sm-4 col-md-3 quantity-group">
                                <label class="form-label">الكمية</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}" required>
                            </div>
                            <div class="col-sm-8 col-md-6">
                                <label class="form-label d-none d-sm-block">&nbsp;</label>
                                <button type="submit" class="btn btn-add-cart w-100">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>إضافة للسلة</span>
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="form-label d-none d-sm-block">&nbsp;</label>
                                <button type="button" class="btn btn-wishlist w-100 wishlist-btn" data-product-slug="{{ $product->slug ?? $product->id }}" title="المفضلة">
                                    <i class="fas fa-heart {{ $isInWishlist ? 'text-danger' : 'text-muted' }}"></i>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('login') }}" class="btn btn-add-cart flex-grow-1">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>سجل الدخول لإضافة للسلة</span>
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-wishlist" title="تسجيل الدخول للمفضلة">
                                <i class="fas fa-heart text-muted"></i>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mb-5">
            <h3 class="mb-4">منتجات مشابهة</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                @if($relatedProduct->is_featured)
                                    <span class="product-badge">مميز</span>
                                @endif
                                <a href="{{ route('products.show', $relatedProduct->slug ?? $relatedProduct->id) }}">
                                    <img src="{{ $relatedProduct->main_image ? asset('storage/' . $relatedProduct->main_image) : asset('images/no-product.svg') }}" 
                                         alt="{{ $relatedProduct->name }}" class="product-image"
                                         onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                                </a>
                            </div>
                            <div class="product-info">
                                <h5 class="product-title">
                                    <a href="{{ route('products.show', $relatedProduct->slug ?? $relatedProduct->id) }}" class="text-decoration-none text-dark">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h5>
                                <div class="product-category">
                                    <span class="badge">{{ $relatedProduct->category->name ?? 'عام' }}</span>
                                </div>
                                <div class="product-price">
                                    {{ number_format($relatedProduct->retail_price, 2) }} ج.م
                                </div>
                                <div class="product-actions">
                                    @auth
                                        @php
                                            $isInWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                                ->where('product_id', $relatedProduct->id)
                                                ->exists();
                                        @endphp
                                        <a href="{{ route('cart.add', $relatedProduct->slug ?? $relatedProduct->id) }}" class="btn btn-add-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                            <span>إضافة للسلة</span>
                                        </a>
                                        <button type="button" class="btn btn-wishlist wishlist-btn" data-product-slug="{{ $relatedProduct->slug ?? $relatedProduct->id }}" title="المفضلة">
                                            <i class="fas fa-heart {{ $isInWishlist ? 'text-danger' : 'text-muted' }}"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('cart.add', $relatedProduct->slug ?? $relatedProduct->id) }}" class="btn btn-add-cart">
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
        </div>
    @endif
</div>
@endsection
