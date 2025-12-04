@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('content')
<!-- Hero Banner -->
@php
    // إذا لم يكن هناك sliders، استخدم sliders افتراضية
    if (!$sliders || $sliders->count() == 0) {
        $sliders = collect([
            (object)[
                'title' => 'طلبات الجملة',
                'description' => 'أسعار مميزة للجملة',
                'link' => route('categories.index'),
                'background_color' => '#00a88e',
                'text_color' => 'light',
                'image' => null
            ],
            (object)[
                'title' => 'عروض خاصة',
                'description' => 'خصومات تصل إلى 50%',
                'link' => route('products.index', ['featured' => 1]),
                'background_color' => '#ffd500',
                'text_color' => 'dark',
                'image' => null
            ],
            (object)[
                'title' => 'منتجات جديدة',
                'description' => 'اكتشف أحدث المنتجات',
                'link' => route('products.index'),
                'background_color' => '#404553',
                'text_color' => 'light',
                'image' => null
            ]
        ]);
    }
@endphp

@if($sliders && $sliders->count() > 0)
<div class="hero-banner-section mb-4">
    <div class="container">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                @foreach($sliders as $index => $slider)
                    @php
                        $textColor = $slider->text_color ?? ($slider->background_color == '#ffd500' || strpos($slider->background_color, '#ffd') !== false ? 'dark' : 'light');
                        $titleColor = $textColor == 'dark' ? '#222' : '#fff';
                        $descColor = $textColor == 'dark' ? '#444' : '#fff';
                        $iconColor = $textColor == 'dark' ? 'rgba(0,0,0,0.2)' : 'rgba(255,255,255,0.3)';
                    @endphp
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ $slider->link ?? '#' }}" class="d-block text-decoration-none">
                            <div class="hero-banner" style="background: {{ $slider->background_color }}; border-radius: 8px; padding: 40px; min-height: 500px; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
                                @if($slider->image)
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('{{ asset('storage/' . $slider->image) }}'); background-size: cover; background-position: center; opacity: 0.3;"></div>
                                @endif
                                <div class="hero-content" style="position: relative; z-index: 1;">
                                    <h2 style="font-size: 2rem; font-weight: 700; color: {{ $titleColor }}; margin-bottom: 8px;">
                                        {{ $slider->title }}
                                    </h2>
                                    @if($slider->description)
                                        <p style="font-size: 1rem; color: {{ $descColor }}; margin: 0;">
                                            {{ $slider->description }}
                                        </p>
                                    @endif
                                </div>
                                <div class="d-none d-md-block" style="position: relative; z-index: 1;">
                                    <i class="fas fa-arrow-left" style="font-size: 3rem; color: {{ $iconColor }};"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            @if($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                <div class="carousel-indicators">
                    @foreach($sliders as $index => $slider)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Quick Benefits -->
<div class="benefits-section mb-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="benefit-card">
                    <i class="fas fa-truck-fast"></i>
                    <div>
                        <div class="benefit-title">توصيل سريع</div>
                        <div class="benefit-subtitle">لكل المحافظات</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-card">
                    <i class="fas fa-tags"></i>
                    <div>
                        <div class="benefit-title">أفضل الأسعار</div>
                        <div class="benefit-subtitle">عروض يومية</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-card">
                    <i class="fas fa-shield-halved"></i>
                    <div>
                        <div class="benefit-title">دفع آمن</div>
                        <div class="benefit-subtitle">حماية كاملة</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="benefit-card">
                    <i class="fas fa-rotate-left"></i>
                    <div>
                        <div class="benefit-title">استرجاع سهل</div>
                        <div class="benefit-subtitle">وفق الشروط</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Featured Products -->
    <div class="products-section mb-5">
        <div class="section-header mb-4">
            <h2 class="section-title">منتجات مميزة</h2>
            <a href="{{ route('products.index', ['featured' => 1]) }}" class="view-all-btn">
                عرض الكل <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <div class="row">
            @forelse($featuredProducts ?? [] as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
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
                                <span class="badge">{{ $product->category->name ?? 'عام' }}</span>
                            </div>
                            <div class="product-price">
                                {{ number_format($product->retail_price, 2) }} ج.م
                            </div>
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
                        <h4>لا توجد منتجات متاحة حالياً</h4>
                        <p>يرجى إضافة منتجات من لوحة التحكم</p>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">إضافة منتج جديد</a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- New Arrivals -->
    @if(isset($newProducts) && $newProducts->count() > 0)
        <div class="products-section mb-5">
            <div class="section-header mb-4">
                <h2 class="section-title">وصل حديثاً</h2>
                <a href="{{ route('products.index') }}" class="view-all-btn">
                    عرض الكل <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="row">
                @foreach($newProducts->take(4) as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
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
                                    <span class="badge">{{ $product->category->name ?? 'عام' }}</span>
                                </div>
                                <div class="product-price">
                                    {{ number_format($product->retail_price, 2) }} ج.م
                                </div>
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
        </div>
    @endif
</div>
@endsection
