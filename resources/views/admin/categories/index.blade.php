@extends('layouts.admin')

@section('title', 'إدارة التصنيفات')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h1><i class="fas fa-tags"></i> إدارة التصنيفات</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة تصنيف جديد
    </a>
</div>

<div class="card shadow-sm p-3 mb-4">
    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="is_active" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">بحث</label>
            <input type="text" name="search" class="form-control" placeholder="اسم التصنيف أو الرابط" value="{{ request('search') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> بحث
            </button>
        </div>
    </form>
</div>

@if($categories->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> لا توجد تصنيفات مطابقة.
    </div>
@else
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>الرابط</th>
                        <th>التصنيف الأب</th>
                        <th>عدد المنتجات</th>
                        <th>ترتيب العرض</th>
                        <th>الحالة</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; border-radius: 5px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <div class="text-muted small">{{ Str::limit($category->description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <code class="small">{{ $category->slug }}</code>
                            </td>
                            <td>
                                @if($category->parent)
                                    <span class="badge bg-info">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products->count() }}</span>
                            </td>
                            <td>{{ $category->sort_order }}</td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                      style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
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
            {{ $categories->appends(request()->query())->links() }}
        </div>
    </div>
@endif
@endsection

