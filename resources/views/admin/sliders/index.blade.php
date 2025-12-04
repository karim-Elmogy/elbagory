@extends('layouts.admin')

@section('title', 'إدارة الـ Sliders')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h1><i class="fas fa-images"></i> إدارة الـ Sliders</h1>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة slider جديد
    </a>
</div>

@if($sliders->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> لا توجد sliders حالياً. قم بإضافة slider جديد.
    </div>
@else
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>الرابط</th>
                        <th>لون الخلفية</th>
                        <th>ترتيب العرض</th>
                        <th>الحالة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $slider)
                        <tr>
                            <td>{{ $slider->id }}</td>
                            <td>
                                @if($slider->image)
                                    <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" 
                                         style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 50px; border-radius: 5px; background-color: {{ $slider->background_color }};">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $slider->title }}</strong>
                            </td>
                            <td>
                                <div class="text-muted small">{{ Str::limit($slider->description ?? '-', 50) }}</div>
                            </td>
                            <td>
                                @if($slider->link)
                                    <a href="{{ $slider->link }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-external-link-alt"></i> رابط
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div style="width: 30px; height: 30px; background-color: {{ $slider->background_color }}; border-radius: 4px; border: 1px solid #ddd;"></div>
                                <small class="text-muted d-block">{{ $slider->background_color }}</small>
                            </td>
                            <td>{{ $slider->sort_order }}</td>
                            <td>
                                <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $slider->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" 
                                      style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الـ slider؟');">
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
    </div>
@endif
@endsection

