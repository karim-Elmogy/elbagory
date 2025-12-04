<!doctype html>
<html class="no-js" lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'لوحة التحكم') - لوحة التحكم</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        
        .alert-dismissible .btn-close
        {
         right:unset !important;
         left: 0px !important;
        }
        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 0;
            position: fixed;
            width: 250px;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.3rem;
            text-align: center;
        }
        
        .sidebar nav {
            padding: 20px 0;
        }
        
        .sidebar nav a {
            color: rgba(255,255,255,0.9);
            padding: 15px 25px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
            font-size: 0.95rem;
        }
        
        .sidebar nav a i {
            width: 25px;
            margin-left: 10px;
            font-size: 1.1rem;
        }
        
        .sidebar nav a:hover {
            background-color: rgba(255,255,255,0.1);
            border-right-color: #3498db;
            color: white;
            padding-right: 30px;
        }
        
        .sidebar nav a.active {
            background-color: rgba(52, 152, 219, 0.2);
            border-right-color: #3498db;
            color: white;
            font-weight: 600;
        }
        
        .sidebar hr {
            border-color: rgba(255,255,255,0.2);
            margin: 15px 0;
        }
        
        .sidebar .logout-btn {
            background: none;
            border: none;
            color: rgba(255,255,255,0.9);
            padding: 15px 25px;
            width: 100%;
            text-align: right;
            cursor: pointer;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
            font-size: 0.95rem;
        }
        
        .sidebar .logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.2);
            border-right-color: #e74c3c;
            color: white;
        }
        
        .sidebar .logout-btn i {
            width: 25px;
            margin-left: 10px;
        }
        
        /* Main Content */
        .main-content {
            margin-right: 250px;
            padding: 30px;
            min-height: 100vh;
            width: calc(100% - 250px);
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
            border-top: 4px solid #3498db;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .stat-card.primary { border-top-color: #3498db; }
        .stat-card.success { border-top-color: #2ecc71; }
        .stat-card.warning { border-top-color: #f39c12; }
        .stat-card.danger { border-top-color: #e74c3c; }
        .stat-card.info { border-top-color: #1abc9c; }
        .stat-card.purple { border-top-color: #9b59b6; }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(52, 152, 219, 0.05);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        
        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            margin: 0;
            color: #7f8c8d;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card-icon {
            font-size: 2rem;
            opacity: 0.2;
            color: #3498db;
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            line-height: 1.2;
            word-break: break-word;
        }
        
        .stat-card .number small {
            font-size: 1rem;
            color: #7f8c8d;
            font-weight: 400;
            display: block;
            margin-top: 5px;
        }
        
        /* Table Cards */
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow-x: auto;
        }
        
        .table-card h3 {
            margin: 0 0 20px 0;
            color: #2c3e50;
            font-size: 1.2rem;
            font-weight: 700;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .table-card .table {
            margin: 0;
        }
        
        .table-card .table thead th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            border: none;
            padding: 12px;
            font-size: 0.9rem;
        }
        
        .table-card .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-color: #ecf0f1;
        }
        
        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Close button في الجهة اليسار للـ RTL */
        .alert .btn-close {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .mobile-menu-toggle:hover {
            background: #34495e;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
                width: 280px;
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                overflow-y: auto;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .main-content {
                margin-right: 0;
                padding: 70px 15px 20px;
                width: 100%;
            }
            
            .stat-card {
                margin-bottom: 15px;
            }
            
            .stat-card .number {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 70px 10px 15px;
            }
            
            .page-header {
                padding: 15px 0;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .stat-card {
                padding: 20px 15px;
            }
            
            .stat-card h3 {
                font-size: 0.85rem;
            }
            
            .stat-card .number {
                font-size: 1.8rem;
            }
            
            .stat-card-icon {
                font-size: 1.5rem;
            }
            
            .table-card {
                padding: 15px;
            }
            
            .table-card h3 {
                font-size: 1rem;
            }
            
            .btn {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
            }
            
            .sidebar-header h4 {
                font-size: 1.1rem;
            }
            
            .sidebar nav a {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
            
            .main-content {
                padding: 70px 5px 10px;
            }
            
            .page-header h1 {
                font-size: 1.3rem;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-card .number {
                font-size: 1.5rem;
            }
            
            .stat-card h3 {
                font-size: 0.8rem;
            }
            
            .stat-card-icon {
                font-size: 1.2rem;
            }
            
            .table-card {
                padding: 12px;
                overflow-x: auto;
            }
            
            .table-card h3 {
                font-size: 0.95rem;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 8px 5px;
            }
            
            .btn {
                font-size: 0.85rem;
                padding: 6px 12px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <h4><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h4>
                </div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> الرئيسية
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i> التصنيفات
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-cube"></i> المنتجات
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> العملاء
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i> الطلبات
                    </a>
                    <a href="{{ route('admin.invoices.index') }}" class="{{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i> فواتير الجملة
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="fas fa-tag"></i> الخصومات
                    </a>
                    <a href="{{ route('admin.sliders.index') }}" class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i> الـ Sliders
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> التقارير
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> الإعدادات
                    </a>
                    <hr>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-globe"></i> الموقع الرئيسي
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                        </button>
                    </form>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            }
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
            
            // Close sidebar when clicking on a link (mobile)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        toggleSidebar();
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
