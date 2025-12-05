<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PricingRequestController;
use App\Http\Controllers\Admin\AdminPricingRequestController;

// ============================================
// الواجهة الأمامية - عامة (بدون مصادقة)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// الأقسام
Route::get('/categories', [HomeController::class, 'categories'])->name('categories.index');

// المنتجات
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');

    // السلة - عرض فقط بدون مصادقة
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// إضافة للسلة - يدعم GET و POST
Route::get('/cart/add/{slug}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add/{slug}', [CartController::class, 'add']);
// تطبيق وإزالة كود الخصم
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

// ============================================
// المصادقة - للزوار فقط (guest)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ============================================
// صفحات العميل - تتطلب مصادقة
// ============================================
Route::middleware('auth')->group(function () {
    // السلة - عمليات تتطلب مصادقة
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // الطلبات
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/wholesale', [OrderController::class, 'wholesaleOrders'])->name('orders.wholesale');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // حساب العميل
    Route::get('/account', [CustomerController::class, 'index'])->name('customer.account');
    Route::put('/account', [CustomerController::class, 'update'])->name('customer.update');
    
    // المفضلة
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{slug}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    
    // الإشعارات
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // طلبات التسعير (لعملاء الجملة فقط)
    Route::get('/pricing-requests', [PricingRequestController::class, 'index'])->name('pricing-requests.index');
    Route::get('/pricing-requests/create', [PricingRequestController::class, 'create'])->name('pricing-requests.create');
    Route::post('/pricing-requests', [PricingRequestController::class, 'store'])->name('pricing-requests.store');
    Route::get('/pricing-requests/{id}', [PricingRequestController::class, 'show'])->name('pricing-requests.show');
    
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ============================================
// لوحة التحكم - تتطلب مصادقة + دور admin
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard');
    
    // المنتجات
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('products', AdminProductController::class);
    });
    
    // العملاء
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('customers', AdminCustomerController::class);
    });
    
    // الطلبات
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('orders', AdminOrderController::class);
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
    
    // الفواتير
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [AdminInvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [AdminInvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{id}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{id}/edit', [AdminInvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('/invoices/{id}', [AdminInvoiceController::class, 'update'])->name('invoices.update');
        Route::post('/invoices/{id}/cancel', [AdminInvoiceController::class, 'cancel'])->name('invoices.cancel');
        Route::get('/invoices/{id}/print', [AdminInvoiceController::class, 'print'])->name('invoices.print');
        Route::get('/invoices/{id}/pdf', [AdminInvoiceController::class, 'pdf'])->name('invoices.pdf');
    });
    
    // الخصومات
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('coupons', AdminCouponController::class);
    });
    
    // التصنيفات
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('categories', AdminCategoryController::class);
    });
    
    // التقارير
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/products', [ReportController::class, 'products'])->name('reports.products');
        Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
        Route::get('/reports/invoices', [ReportController::class, 'invoices'])->name('reports.invoices');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    });
    
    // الإعدادات
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
    
    // الـ Sliders
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('sliders', AdminSliderController::class);
    });
    
    // طلبات التسعير
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/pricing-requests', [AdminPricingRequestController::class, 'index'])->name('pricing-requests.index');
        Route::get('/pricing-requests/{id}', [AdminPricingRequestController::class, 'show'])->name('pricing-requests.show');
        Route::post('/pricing-requests/{id}/prices', [AdminPricingRequestController::class, 'updatePrices'])->name('pricing-requests.updatePrices');
        Route::post('/pricing-requests/{id}/status', [AdminPricingRequestController::class, 'updateStatus'])->name('pricing-requests.updateStatus');
    });
});
