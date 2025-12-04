@extends('layouts.admin')

@section('title', 'إدارة المنتجات')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <h1><i class="fas fa-cube"></i> المنتجات</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة منتج جديد
    </a>
</div>

@if($products->isEmpty())
    <div class="alert alert-info">
        لا توجد منتجات حالياً. قم بإضافة منتج جديد.
    </div>
@else
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>المنتج</th>
                        <th>التصنيف</th>
                        <th>الأسعار</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/no-product.svg') }}" 
                                     class="rounded" 
                                     style="width:60px;height:60px;object-fit:cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <div class="text-muted small">
                                    كود: {{ $product->code }}<br>
                                    وحدة: {{ $product->unit }}
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <div class="fw-bold text-success">{{ number_format($product->retail_price, 2) }} ج.م</div>
                                <div class="text-muted small">جملة: {{ number_format($product->wholesale_price, 2) }} ج.م</div>
                            </td>
                            <td>
                                <span class="badge {{ $product->stock_quantity <= $product->reorder_level ? 'bg-danger' : 'bg-info' }}">
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                                <div class="text-muted small">حد الإعادة: {{ $product->reorder_level }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->is_active ? 'نشط' : 'متوقف' }}
                                </span>
                                @if($product->is_featured)
                                    <span class="badge bg-warning text-dark">مميز</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $products->links() }}
        </div>
    </div>
@endif
@endsection

