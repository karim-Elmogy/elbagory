<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\Coupon;
use App\Models\Setting;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // تم نقل الـ middleware إلى الرواتر

    public function index()
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer) {
            return redirect()->route('customer.account')->with('error', 'يرجى إكمال بيانات حسابك');
        }

        $orders = Order::where('customer_id', $customer->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function wholesaleOrders()
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer) {
            return redirect()->route('customer.account')->with('error', 'يرجى إكمال بيانات حسابك');
        }

        if (!$customer->isWholesale()) {
            return redirect()->route('orders.index')->with('error', 'هذه الصفحة متاحة لعملاء الجملة فقط');
        }

        $orders = Order::where('customer_id', $customer->id)
            ->where('type', 'wholesale')
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.wholesale', compact('orders'));
    }

    public function show($id)
    {
        $customer = auth()->user()->customers()->first();
        $order = Order::where('id', $id)
            ->where('customer_id', $customer->id)
            ->with('items.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer) {
            return redirect()->route('customer.account')->with('error', 'يرجى إكمال بيانات حسابك');
        }

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        // التحقق من المخزون
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product || !$product->hasStock($quantity)) {
                return redirect()->route('cart.index')->with('error', "الكمية المتاحة غير كافية للمنتج: {$product->name}");
            }
        }

        // إنشاء الطلب
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);
        $customerType = $customer->type;
        
        $subtotal = 0;
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            $price = $product->getPriceForCustomer($customerType);
            $total = $price * $quantity;
            $subtotal += $total;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
            ];
        }

        // تطبيق كود الخصم إن وجد
        $couponCode = Session::get('coupon_code');
        $coupon = null;
        $couponDiscount = 0;

        if ($couponCode) {
            $coupon = Coupon::byCode($couponCode)->first();
            if ($coupon) {
                $validation = $coupon->isValid($customerType, $subtotal, auth()->id());
                if ($validation['valid']) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                    // زيادة عدد مرات الاستخدام
                    $coupon->incrementUsage();
                }
            }
        }

        $shippingCost = 0; // يمكن حسابها لاحقاً
        $discount = $couponDiscount; // الخصم من الكوبون
        
        // حساب الضريبة إذا كانت مفعلة
        $tax = 0;
        if (Setting::isTaxEnabled()) {
            $taxRate = Setting::getTaxRate();
            $tax = ($subtotal - $discount) * ($taxRate / 100);
        }
        
        $total = $subtotal - $discount + $shippingCost + $tax;

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'coupon_id' => $coupon ? $coupon->id : null,
            'type' => $customerType,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon_discount' => $couponDiscount,
            'shipping_cost' => $shippingCost,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'shipping_address' => $customer->address,
            'created_by' => auth()->id(),
        ]);

        // إنشاء عناصر الطلب وتحديث المخزون
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'discount' => 0,
                'total' => $item['total'],
            ]);

            // تحديث المخزون
            $product = $item['product'];
            $quantityBefore = $product->stock_quantity;
            $product->stock_quantity -= $item['quantity'];
            $product->save();

            // تسجيل حركة المخزون
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $item['quantity'],
                'quantity_before' => $quantityBefore,
                'quantity_after' => $product->stock_quantity,
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'notes' => 'طلب رقم: ' . $order->order_number,
                'created_by' => auth()->id(),
            ]);
        }

        // تفريغ السلة وإزالة كود الخصم بعد إنشاء الطلب بنجاح
        Session::forget('cart');
        Session::forget('coupon_code');
        // التأكد من حفظ التغييرات في Session
        $request->session()->save();

        // إنشاء إشعار للمستخدم
        NotificationHelper::success(
            'تم إنشاء الطلب بنجاح',
            'تم إنشاء طلبك بنجاح! رقم الطلب: ' . $orderNumber,
            auth()->id(),
            route('orders.show', $order->id)
        );

        $successMessage = 'تم إنشاء الطلب بنجاح! تم تفريغ السلة.';
        if ($coupon) {
            $successMessage .= ' تم تطبيق خصم: ' . number_format($couponDiscount, 2) . ' ج.م';
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', $successMessage);
    }
}
