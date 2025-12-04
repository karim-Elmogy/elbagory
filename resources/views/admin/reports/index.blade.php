@extends('layouts.admin')

@section('title', 'التقارير')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> التقارير</h1>
</div>

<div class="row g-4">
    <!-- تقرير المبيعات -->
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-money-bill-wave fa-3x text-success"></i>
                </div>
                <h5 class="card-title">تقرير المبيعات</h5>
                <p class="text-muted">عرض المبيعات حسب الفترة والنوع</p>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-success">
                    <i class="fas fa-arrow-left"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <!-- تقرير المنتجات -->
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-cube fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">تقرير المنتجات</h5>
                <p class="text-muted">الأكثر مبيعاً والأقل مبيعاً والمخزون</p>
                <a href="{{ route('admin.reports.products') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <!-- تقرير العملاء -->
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x text-info"></i>
                </div>
                <h5 class="card-title">تقرير العملاء</h5>
                <p class="text-muted">أفضل العملاء والعملاء الجدد</p>
                <a href="{{ route('admin.reports.customers') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <!-- تقرير الطلبات -->
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-shopping-cart fa-3x text-warning"></i>
                </div>
                <h5 class="card-title">تقرير الطلبات</h5>
                <p class="text-muted">تفاصيل الطلبات حسب الحالة والنوع</p>
                <a href="{{ route('admin.reports.orders') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <!-- تقرير الفواتير -->
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-file-invoice fa-3x text-danger"></i>
                </div>
                <h5 class="card-title">تقرير الفواتير</h5>
                <p class="text-muted">فواتير الجملة والمدفوعات</p>
                <a href="{{ route('admin.reports.invoices') }}" class="btn btn-danger">
                    <i class="fas fa-arrow-left"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <!-- تقرير المخزون -->
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-warehouse fa-3x text-purple"></i>
                </div>
                <h5 class="card-title">تقرير المخزون</h5>
                <p class="text-muted">حالة المخزون والمنتجات المنخفضة</p>
                <a href="{{ route('admin.reports.inventory') }}" class="btn" style="background: #9b59b6; color: white;">
                    <i class="fas fa-arrow-left"></i> عرض التقرير
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

