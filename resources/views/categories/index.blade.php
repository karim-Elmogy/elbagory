@extends('layouts.app')

@section('title', 'الأقسام')

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
                    الأقسام
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Categories Content -->
<div class="container my-5">
    <!-- Categories Header -->
    <div class="categories-page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="categories-page-title mb-2">
                    <i class="fas fa-th-large text-primary"></i>
                    الأقسام
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    تصفح جميع أقسام المنتجات المتاحة
                </p>
            </div>
            <div class="categories-count-badge">
                <span class="badge bg-primary" style="font-size: 14px; padding: 10px 20px;">
                    <i class="fas fa-folder"></i>
                    {{ $categories->count() }} قسم
                </span>
            </div>
        </div>
    </div>
    
    @if($categories->count() > 0)
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="category-card">
                        <div class="category-image-wrapper">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="category-image"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                            @else
                                <div class="category-image-placeholder">
                                    <i class="fas fa-folder"></i>
                                </div>
                            @endif
                            <div class="category-overlay">
                                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-view-category">
                                    <i class="fas fa-eye"></i>
                                    عرض المنتجات
                                </a>
                            </div>
                        </div>
                        <div class="category-info">
                            <h4 class="category-name">
                                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="text-decoration-none">
                                    {{ $category->name }}
                                </a>
                            </h4>
                            @if($category->description)
                                <p class="category-description">
                                    {{ \Illuminate\Support\Str::limit($category->description, 100) }}
                                </p>
                            @endif
                            <div class="category-stats">
                                <div class="category-stat-item">
                                    <i class="fas fa-box text-primary"></i>
                                    <span>{{ $category->products->count() }} منتج</span>
                                </div>
                                @if($category->children->count() > 0)
                                    <div class="category-stat-item">
                                        <i class="fas fa-folder-open text-success"></i>
                                        <span>{{ $category->children->count() }} قسم فرعي</span>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-primary w-100 mt-3">
                                <i class="fas fa-arrow-left"></i>
                                تصفح القسم
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-categories">
            <div class="empty-categories-icon">
                <i class="fas fa-folder"></i>
            </div>
            <h3 class="empty-categories-title">لا توجد أقسام</h3>
            <p class="empty-categories-text">لا توجد أقسام متاحة حالياً</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-box"></i>
                عرض جميع المنتجات
            </a>
        </div>
    @endif
</div>
@endsection

