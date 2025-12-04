<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // تم نقل الـ middleware إلى الرواتر

    public function index()
    {
        $today = now()->startOfDay();
        
        // إحصائيات اليوم
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todaySales = Order::whereDate('created_at', $today)->sum('total');
        $todayRetailSales = Order::whereDate('created_at', $today)
            ->where('type', 'retail')
            ->sum('total');
        $todayWholesaleSales = Order::whereDate('created_at', $today)
            ->where('type', 'wholesale')
            ->sum('total');
        
        // أكثر المنتجات مبيعاً
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
        
        // تحذيرات المخزون المنخفض
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->where('is_active', true)
            ->take(10)
            ->get();
        
        // إحصائيات الشهر
        $monthStart = now()->startOfMonth();
        $monthOrders = Order::where('created_at', '>=', $monthStart)->count();
        $monthSales = Order::where('created_at', '>=', $monthStart)->sum('total');
        
        // إحصائيات العملاء
        $totalCustomers = Customer::count();
        $retailCustomers = Customer::where('type', 'retail')->count();
        $wholesaleCustomers = Customer::where('type', 'wholesale')->count();
        
        // إحصائيات الفواتير
        $totalInvoices = Invoice::count();
        $pendingInvoices = Invoice::where('status', 'draft')->count();
        $paidInvoices = Invoice::where('status', 'final')->count();
        
        return view('admin.dashboard', compact(
            'todayOrders',
            'todaySales',
            'todayRetailSales',
            'todayWholesaleSales',
            'topProducts',
            'lowStockProducts',
            'monthOrders',
            'monthSales',
            'totalCustomers',
            'retailCustomers',
            'wholesaleCustomers',
            'totalInvoices',
            'pendingInvoices',
            'paidInvoices'
        ));
    }
}
