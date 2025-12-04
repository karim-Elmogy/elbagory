<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $customerType = auth()->user()?->customers()->first()?->type ?? 'retail';
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
        }

        // الحصول على كود الخصم من Session
        $couponCode = Session::get('coupon_code');
        $coupon = null;
        $couponDiscount = 0;
        $couponMessage = null;

        if ($couponCode) {
            $coupon = Coupon::byCode($couponCode)->first();
            if ($coupon) {
                $customerType = auth()->user()?->customers()->first()?->type ?? 'retail';
                $userId = auth()->id();
                $validation = $coupon->isValid($customerType, $subtotal, $userId);
                
                if ($validation['valid']) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                } else {
                    $couponMessage = $validation['message'];
                    Session::forget('coupon_code');
                    $coupon = null;
                }
            } else {
                Session::forget('coupon_code');
            }
        }

        // حساب الضريبة إذا كانت مفعلة
        $tax = 0;
        $taxRate = 0;
        if (Setting::isTaxEnabled()) {
            $taxRate = Setting::getTaxRate();
            $tax = ($subtotal - $couponDiscount) * ($taxRate / 100);
        }

        $total = $subtotal - $couponDiscount + $tax;

        return view('cart.index', compact('items', 'subtotal', 'coupon', 'couponDiscount', 'tax', 'taxRate', 'total', 'couponMessage'));
    }

    /**
     * تطبيق كود الخصم
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $couponCode = strtoupper(trim($request->input('coupon_code')));
        $coupon = Coupon::byCode($couponCode)->first();

        if (!$coupon) {
            return back()->with('error', 'كود الخصم غير صحيح');
        }

        // حساب الإجمالي الحالي
        $cart = Session::get('cart', []);
        $subtotal = 0;
        $customerType = auth()->user()?->customers()->first()?->type ?? 'retail';

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $price = $product->getPriceForCustomer($customerType);
                $subtotal += $price * $quantity;
            }
        }

        // التحقق من صلاحية الخصم
        $userId = auth()->id();
        $validation = $coupon->isValid($customerType, $subtotal, $userId);

        if (!$validation['valid']) {
            return back()->with('error', $validation['message']);
        }

        // حفظ كود الخصم في Session
        Session::put('coupon_code', $couponCode);

        return back()->with('success', 'تم تطبيق كود الخصم بنجاح!');
    }

    /**
     * إزالة كود الخصم
     */
    public function removeCoupon()
    {
        Session::forget('coupon_code');
        return back()->with('success', 'تم إزالة كود الخصم');
    }

    public function add(Request $request, $slug)
    {
        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يرجى تسجيل الدخول أولاً لإضافة المنتجات للسلة');
        }

        $product = Product::where('slug', $slug)->first();
        
        // Fallback: إذا لم يتم العثور على المنتج بالـ slug، جرب البحث بالـ id
        if (!$product && is_numeric($slug)) {
            $product = Product::find($slug);
            if ($product && $product->slug) {
                return redirect()->route('cart.add', $product->slug, 301);
            }
        }
        
        if (!$product) {
            abort(404, 'المنتج غير موجود');
        }
        
        // التأكد من وجود slug
        if (empty($product->slug)) {
            $product->slug = \Illuminate\Support\Str::slug($product->name);
            $product->save();
        }

        if (!$product->is_active) {
            return back()->with('error', 'المنتج غير متاح');
        }

        $cart = Session::get('cart', []);
        // الحصول على الكمية من الـ request أو استخدام 1 كقيمة افتراضية
        $quantity = (int) $request->input('quantity', 1);
        
        // التأكد من أن الكمية صحيحة
        if ($quantity < 1) {
            $quantity = 1;
        }

        // التحقق من المخزون
        $currentQuantity = isset($cart[$product->id]) ? (int) $cart[$product->id] : 0;
        $totalQuantity = $currentQuantity + $quantity;
        
        if ($product->stock_quantity < $totalQuantity) {
            return back()->with('error', 'الكمية المتاحة غير كافية. المتاح: ' . $product->stock_quantity);
        }

        // إضافة أو تحديث الكمية في السلة
        if (isset($cart[$product->id])) {
            $cart[$product->id] += $quantity;
        } else {
            $cart[$product->id] = $quantity;
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'تمت إضافة المنتج للسلة بنجاح');
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        if ($quantity <= 0) {
            return $this->remove($request);
        }

        if (!$product->hasStock($quantity)) {
            return back()->with('error', 'الكمية المتاحة غير كافية');
        }

        $cart = Session::get('cart', []);
        $cart[$productId] = $quantity;
        Session::put('cart', $cart);

        return back()->with('success', 'تم تحديث الكمية');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'تم حذف المنتج من السلة');
    }

    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'تم تفريغ السلة');
    }
}
