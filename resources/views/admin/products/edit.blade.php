@extends('layouts.admin')

@section('title', 'تعديل منتج')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <h1><i class="fas fa-edit"></i> تعديل المنتج</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
    @csrf
    @method('PUT')
    @include('admin.products._form')
    <div class="mt-4 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> تحديث المنتج
        </button>
    </div>
</form>
@endsection

