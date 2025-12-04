@extends('layouts.admin')

@section('title', 'تفاصيل التصنيف')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <div>
        <h1><i class="fas fa-tag"></i> {{ $category->name }}</h1>
        <div class="text-muted">رقم التصنيف: #{{ $category->id }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> تعديل
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- معلومات التصنيف -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات التصنيف</h5>
                
                <div class="row mb-3">
                    <div class="col-4"><strong>الاسم:</strong></div>
                    <div class="col-8">{{ $category->name }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4"><strong>الرابط:</strong></div>
                    <div class="col-8"><code>{{ $category->slug }}</code></div>
                </div>
                
                @if($category->description)
                    <div class="row mb-3">
                        <div class="col-4"><strong>الوصف:</strong></div>
                        <div class="col-8">{{ $category->description }}</div>
                    </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-4"><strong>التصنيف الأب:</strong></div>
                    <div class="col-8">
                        @if($category->parent)
                            <a href="{{ route('admin.categories.show', $category->parent->id) }}" class="badge bg-info">
                                {{ $category->parent->name }}
                            </a>
                        @else
                            <span class="text-muted">تصنيف رئيسي</span>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4"><strong>ترتيب العرض:</strong></div>
                    <div class="col-8">{{ $category->sort_order }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4"><strong>الحالة:</strong></div>
                    <div class="col-8">
                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4"><strong>تاريخ الإنشاء:</strong></div>
                    <div class="col-8">{{ $category->created_at->format('Y-m-d H:i') }}</div>
                </div>
                
                <div class="row">
                    <div class="col-4"><strong>آخر تحديث:</strong></div>
                    <div class="col-8">{{ $category->updated_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>
        
        @if($category->image)
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">صورة التصنيف</h5>
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                         class="img-fluid" style="max-height: 400px; border-radius: 5px;">
                </div>
            </div>
        @endif
    </div>
    
    <!-- الإحصائيات -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">الإحصائيات</h5>
                
                <div class="mb-3">
                    <div class="stat-card primary p-3 text-center">
                        <h3 class="mb-1">{{ $category->products->count() }}</h3>
                        <p class="mb-0 small">عدد المنتجات</p>
                    </div>
                </div>
                
                @if($category->children->count() > 0)
                    <div class="mb-3">
                        <div class="stat-card success p-3 text-center">
                            <h3 class="mb-1">{{ $category->children->count() }}</h3>
                            <p class="mb-0 small">التصنيفات الفرعية</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- التصنيفات الفرعية -->
@if($category->children->count() > 0)
    <div class="table-card mt-4">
        <h3><i class="fas fa-sitemap"></i> التصنيفات الفرعية</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرابط</th>
                        <th>عدد المنتجات</th>
                        <th>الحالة</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->children as $child)
                        <tr>
                            <td>{{ $child->id }}</td>
                            <td><strong>{{ $child->name }}</strong></td>
                            <td><code class="small">{{ $child->slug }}</code></td>
                            <td><span class="badge bg-primary">{{ $child->products->count() }}</span></td>
                            <td>
                                <span class="badge {{ $child->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $child->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.categories.show', $child->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- المنتجات -->
@if($category->products->count() > 0)
    <div class="table-card mt-4">
        <h3><i class="fas fa-cube"></i> المنتجات في هذا التصنيف</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>السعر القطاعي</th>
                        <th>السعر الجملة</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->products->take(20) as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ number_format($product->retail_price, 2) }} ج.م</td>
                            <td>{{ number_format($product->wholesale_price, 2) }} ج.م</td>
                            <td>
                                <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($category->products->count() > 20)
            <div class="text-center mt-3">
                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary">
                    عرض جميع المنتجات
                </a>
            </div>
        @endif
    </div>
@endif
@endsection

