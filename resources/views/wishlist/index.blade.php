@extends('layouts.app')

@section('title', 'المفضلة')

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
                    المفضلة
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="container my-5">
    <!-- Wishlist Header -->
    <div class="wishlist-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="wishlist-title mb-2">
                    <i class="fas fa-heart text-danger"></i>
                    المفضلة
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    المنتجات التي أضفتها للمفضلة
                </p>
            </div>
            <div class="wishlist-count-badge">
                <span class="badge bg-danger" style="font-size: 14px; padding: 10px 20px;">
                    <i class="fas fa-heart"></i>
                    {{ $wishlists->total() }} منتج
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($wishlists->count() > 0)
        <div class="row">
            @foreach($wishlists as $wishlist)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card wishlist-product-card">
                        <div class="product-image-wrapper">
                            @if($wishlist->product->is_featured)
                                <span class="product-badge">مميز</span>
                            @endif
                            <div class="wishlist-remove-overlay">
                                <button type="button" 
                                        class="btn btn-remove-wishlist" 
                                        title="حذف من المفضلة"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteWishlistModal"
                                        data-wishlist-id="{{ $wishlist->id }}"
                                        data-product-name="{{ $wishlist->product->name }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <a href="{{ route('products.show', $wishlist->product->slug ?? $wishlist->product->id) }}">
                                <img src="{{ $wishlist->product->main_image ? asset('storage/' . $wishlist->product->main_image) : asset('images/no-product.svg') }}" 
                                     alt="{{ $wishlist->product->name }}" class="product-image"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                            </a>
                        </div>
                        <div class="product-info">
                            <h5 class="product-title">
                                <a href="{{ route('products.show', $wishlist->product->slug ?? $wishlist->product->id) }}" class="text-decoration-none text-dark">
                                    {{ $wishlist->product->name }}
                                </a>
                            </h5>
                            <div class="product-category">
                                <span class="badge">{{ $wishlist->product->category->name ?? 'غير مصنف' }}</span>
                            </div>
                            <div class="product-price">
                                @auth
                                    @php
                                        $customerType = auth()->user()->customers()->first()?->type ?? 'retail';
                                        $price = $wishlist->product->getPriceForCustomer($customerType);
                                    @endphp
                                    {{ number_format($price, 2) }} ج.م
                                @else
                                    {{ number_format($wishlist->product->retail_price, 2) }} ج.م
                                @endauth
                            </div>
                            @if($wishlist->product->stock_quantity > 0)
                                <div class="product-stock-info">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span>متوفر في المخزون</span>
                                </div>
                            @else
                                <div class="product-stock-info text-danger">
                                    <i class="fas fa-times-circle"></i>
                                    <span>غير متوفر</span>
                                </div>
                            @endif
                            <div class="product-actions">
                                <a href="{{ route('cart.add', $wishlist->product->slug ?? $wishlist->product->id) }}" class="btn btn-add-cart">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>إضافة للسلة</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($wishlists->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $wishlists->links() }}
            </div>
        @endif
    @else
        <div class="empty-wishlist">
            <div class="empty-wishlist-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h3 class="empty-wishlist-title">المفضلة فارغة</h3>
            <p class="empty-wishlist-text">لم تقم بإضافة أي منتجات للمفضلة بعد</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i>
                تصفح المنتجات
            </a>
        </div>
    @endif
</div>

<!-- Delete Wishlist Modal -->
<div class="modal fade" id="deleteWishlistModal" tabindex="-1" aria-labelledby="deleteWishlistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-wishlist-icon mb-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="modal-title mb-3" id="deleteWishlistModalLabel">تأكيد الحذف</h5>
                <p class="text-muted mb-4">
                    هل أنت متأكد من حذف المنتج <strong id="productNameInModal"></strong> من المفضلة؟
                </p>
                <form id="deleteWishlistForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle delete wishlist modal
        $('#deleteWishlistModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const wishlistId = button.data('wishlist-id');
            const productName = button.data('product-name');
            
            const modal = $(this);
            modal.find('#productNameInModal').text(productName);
            modal.find('#deleteWishlistForm').attr('action', '{{ route("wishlist.remove", ":id") }}'.replace(':id', wishlistId));
        });

        // Handle form submission
        $('#deleteWishlistForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formAction = form.attr('action');
            
            $.ajax({
                url: formAction,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#deleteWishlistModal').modal('hide');
                    // Reload page to show updated wishlist
                    location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = xhr.responseJSON.redirect;
                    } else {
                        alert('حدث خطأ. يرجى المحاولة مرة أخرى.');
                    }
                }
            });
        });

        // Handle wishlist toggle (for other pages)
        $('.wishlist-btn').on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const productSlug = btn.data('product-slug');
            const icon = btn.find('i');

            $.ajax({
                url: '{{ route("wishlist.toggle", ":slug") }}'.replace(':slug', productSlug),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (response.isInWishlist) {
                            icon.removeClass('text-muted').addClass('text-danger');
                            btn.attr('title', 'حذف من المفضلة');
                        } else {
                            icon.removeClass('text-danger').addClass('text-muted');
                            btn.attr('title', 'إضافة للمفضلة');
                        }
                        
                        // تحديث عدد المفضلة في الـ header
                        if (response.wishlistCount > 0) {
                            $('.wishlist-count').text(response.wishlistCount).show();
                        } else {
                            $('.wishlist-count').hide();
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = xhr.responseJSON.redirect;
                    } else {
                        alert('حدث خطأ. يرجى المحاولة مرة أخرى.');
                    }
                }
            });
        });
    });
</script>
@endpush

