@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h1>
</div>

<!-- إحصائيات اليوم -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-shopping-bag"></i> طلبات اليوم</h3>
                <i class="fas fa-shopping-bag stat-card-icon"></i>
            </div>
            <p class="number">{{ $todayOrders }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card success">
            <div class="stat-card-header">
                <h3><i class="fas fa-money-bill-wave"></i> مبيعات اليوم</h3>
                <i class="fas fa-money-bill-wave stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($todaySales, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card info">
            <div class="stat-card-header">
                <h3><i class="fas fa-store"></i> مبيعات قطاعي</h3>
                <i class="fas fa-store stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($todayRetailSales, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <h3><i class="fas fa-warehouse"></i> مبيعات جملة</h3>
                <i class="fas fa-warehouse stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($todayWholesaleSales, 2) }} <small>ج.م</small></p>
        </div>
    </div>
</div>

<!-- إحصائيات الشهر -->
<div class="row g-3 mb-4">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="stat-card purple">
            <div class="stat-card-header">
                <h3><i class="fas fa-calendar-alt"></i> طلبات الشهر</h3>
                <i class="fas fa-calendar-alt stat-card-icon"></i>
            </div>
            <p class="number">{{ $monthOrders }}</p>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="stat-card success">
            <div class="stat-card-header">
                <h3><i class="fas fa-chart-line"></i> مبيعات الشهر</h3>
                <i class="fas fa-chart-line stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($monthSales, 2) }} <small>ج.م</small></p>
        </div>
    </div>
</div>

<!-- إحصائيات العملاء -->
<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-users"></i> إجمالي العملاء</h3>
                <i class="fas fa-users stat-card-icon"></i>
            </div>
            <p class="number">{{ $totalCustomers }}</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="stat-card info">
            <div class="stat-card-header">
                <h3><i class="fas fa-user"></i> عملاء قطاعي</h3>
                <i class="fas fa-user stat-card-icon"></i>
            </div>
            <p class="number">{{ $retailCustomers }}</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <h3><i class="fas fa-building"></i> عملاء جملة</h3>
                <i class="fas fa-building stat-card-icon"></i>
            </div>
            <p class="number">{{ $wholesaleCustomers }}</p>
        </div>
    </div>
</div>

<!-- إحصائيات الفواتير -->
<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-file-invoice"></i> إجمالي الفواتير</h3>
                <i class="fas fa-file-invoice stat-card-icon"></i>
            </div>
            <p class="number">{{ $totalInvoices }}</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <h3><i class="fas fa-file-alt"></i> فواتير مسودة</h3>
                <i class="fas fa-file-alt stat-card-icon"></i>
            </div>
            <p class="number">{{ $pendingInvoices }}</p>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <div class="stat-card success">
            <div class="stat-card-header">
                <h3><i class="fas fa-check-circle"></i> فواتير نهائية</h3>
                <i class="fas fa-check-circle stat-card-icon"></i>
            </div>
            <p class="number">{{ $paidInvoices }}</p>
        </div>
    </div>
</div>

<!-- أكثر المنتجات مبيعاً و تحذيرات المخزون -->
<div class="row g-3">
    <div class="col-lg-6 col-md-12">
        <div class="table-card">
            <h3><i class="fas fa-star text-warning"></i> أكثر المنتجات مبيعاً</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th class="text-center">الكمية المباعة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr>
                                <td>
                                    <i class="fas fa-box text-primary me-2"></i>
                                    {{ $product->name }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $product->total_sold }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i> لا توجد بيانات
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- تحذيرات المخزون -->
    <div class="col-lg-6 col-md-12">
        <div class="table-card">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> تحذيرات المخزون المنخفض</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th class="text-center">الكمية المتاحة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <i class="fas fa-box text-warning me-2"></i>
                                    {{ $product->name }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $product->stock_quantity }} {{ $product->unit }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    <i class="fas fa-check-circle text-success"></i> لا توجد تحذيرات
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
