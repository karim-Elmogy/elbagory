<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        $type = $request->get('type', 'all'); // all, retail, wholesale
        
        $startDate = null;
        $endDate = now();
        
        switch ($period) {
            case 'day':
                $startDate = now()->startOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                break;
            case 'custom':
                $startDate = $request->get('date_from') ? Carbon::parse($request->get('date_from'))->startOfDay() : now()->startOfMonth();
                $endDate = $request->get('date_to') ? Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();
                break;
        }

        $query = Order::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $orders = $query->get();
        
        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // المبيعات حسب اليوم (للرسوم البيانية)
        $salesByDay = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders) {
            return $dayOrders->sum('total');
        });

        // المبيعات حسب الحالة
        $salesByStatus = $orders->groupBy('status')->map(function($statusOrders) {
            return [
                'count' => $statusOrders->count(),
                'total' => $statusOrders->sum('total')
            ];
        });

        // المبيعات حسب طريقة الدفع
        $salesByPayment = $orders->groupBy('payment_method')->map(function($paymentOrders) {
            return [
                'count' => $paymentOrders->count(),
                'total' => $paymentOrders->sum('total')
            ];
        });

        return view('admin.reports.sales', compact(
            'period',
            'type',
            'startDate',
            'endDate',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'salesByDay',
            'salesByStatus',
            'salesByPayment',
            'orders'
        ));
    }

    public function products(Request $request)
    {
        $type = $request->get('type', 'best_selling'); // best_selling, least_selling, low_stock
        
        $startDate = $request->get('date_from') ? Carbon::parse($request->get('date_from'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('date_to') ? Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();

        if ($type === 'best_selling') {
            $products = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    'products.id',
                    'products.name',
                    'products.retail_price',
                    'products.wholesale_price',
                    'products.stock_quantity',
                    'products.unit',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.total) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'products.retail_price', 'products.wholesale_price', 'products.stock_quantity', 'products.unit')
                ->orderBy('total_sold', 'desc')
                ->paginate(20);
        } elseif ($type === 'least_selling') {
            $products = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(
                    'products.id',
                    'products.name',
                    'products.retail_price',
                    'products.wholesale_price',
                    'products.stock_quantity',
                    'products.unit',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.total) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'products.retail_price', 'products.wholesale_price', 'products.stock_quantity', 'products.unit')
                ->orderBy('total_sold', 'asc')
                ->paginate(20);
        } else {
            // low_stock
            $products = Product::whereColumn('stock_quantity', '<=', 'reorder_level')
                ->where('is_active', true)
                ->orderBy('stock_quantity', 'asc')
                ->get();
        }

        return view('admin.reports.products', compact('type', 'startDate', 'endDate', 'products'));
    }

    public function customers(Request $request)
    {
        $type = $request->get('type', 'top'); // top, new
        
        $startDate = $request->get('date_from') ? Carbon::parse($request->get('date_from'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('date_to') ? Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();

        if ($type === 'top') {
            $customers = Customer::with(['orders' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($customer) {
                $customer->orders_count = $customer->orders->count();
                $customer->orders_sum_total = $customer->orders->sum('total');
                return $customer;
            })
            ->sortByDesc('orders_sum_total')
            ->take(20)
            ->values();
            
            // تحويل إلى paginator يدوي
            $perPage = 20;
            $currentPage = request()->get('page', 1);
            $items = $customers->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $customers = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $customers->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // new customers
            $customers = Customer::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('admin.reports.customers', compact('type', 'startDate', 'endDate', 'customers'));
    }

    public function orders(Request $request)
    {
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        
        $startDate = $request->get('date_from') ? Carbon::parse($request->get('date_from'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('date_to') ? Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();

        $query = Order::with('customer')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // إحصائيات
        $totalOrders = $orders->total();
        $totalAmount = $orders->sum('total');
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->selectRaw('status, count(*) as count, sum(total) as total')
            ->get();

        return view('admin.reports.orders', compact('status', 'type', 'startDate', 'endDate', 'orders', 'totalOrders', 'totalAmount', 'ordersByStatus'));
    }

    public function invoices(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $startDate = $request->get('date_from') ? Carbon::parse($request->get('date_from'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->get('date_to') ? Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();

        $query = Invoice::with('customer')
            ->whereBetween('invoice_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(20);

        // إحصائيات
        $totalInvoices = $invoices->total();
        $totalAmount = $invoices->sum('total');
        $paidAmount = $invoices->where('status', 'final')->sum(function($invoice) {
            return $invoice->paid_amount;
        });
        $remainingAmount = $invoices->where('status', 'final')->sum(function($invoice) {
            return $invoice->remaining_amount;
        });

        $invoicesByStatus = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->groupBy('status')
            ->selectRaw('status, count(*) as count, sum(total) as total')
            ->get();

        return view('admin.reports.invoices', compact('status', 'startDate', 'endDate', 'invoices', 'totalInvoices', 'totalAmount', 'paidAmount', 'remainingAmount', 'invoicesByStatus'));
    }

    public function inventory()
    {
        // المنتجات حسب المخزون
        $productsByStock = Product::selectRaw('
            CASE 
                WHEN stock_quantity = 0 THEN "نفد المخزون"
                WHEN stock_quantity <= reorder_level THEN "مخزون منخفض"
                WHEN stock_quantity <= reorder_level * 2 THEN "مخزون متوسط"
                ELSE "مخزون جيد"
            END as stock_status,
            COUNT(*) as count,
            SUM(stock_quantity) as total_stock
        ')
        ->groupBy('stock_status')
        ->get();

        // المنتجات منخفضة المخزون
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // قيمة المخزون
        $totalInventoryValue = Product::sum(DB::raw('stock_quantity * retail_price'));

        // المنتجات الأكثر حركة
        $mostActiveProducts = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('COUNT(*) as movement_count'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('movement_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.inventory', compact(
            'productsByStock',
            'lowStockProducts',
            'totalInventoryValue',
            'mostActiveProducts'
        ));
    }
}

