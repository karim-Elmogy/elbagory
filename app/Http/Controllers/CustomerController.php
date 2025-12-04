<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // تم نقل الـ middleware إلى الرواتر

    public function index()
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer) {
            // إنشاء حساب عميل تلقائياً
            $customer = Customer::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => '',
                'type' => 'retail',
            ]);
        }

        $orders = Order::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.account', compact('customer', 'orders'));
    }

    public function update(Request $request)
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer) {
            return redirect()->route('customer.account')->with('error', 'حساب العميل غير موجود');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'type' => 'required|in:retail,wholesale',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|required_if:type,wholesale',
            'tax_number' => 'nullable|string',
            'detailed_address' => 'nullable|string',
            'preferred_payment_method' => 'nullable|in:cash,bank_transfer,credit',
        ]);

        $customer->update($validated);
        
        // تحديث بيانات المستخدم أيضاً
        auth()->user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? auth()->user()->email,
        ]);

        return redirect()->route('customer.account')->with('success', 'تم تحديث البيانات بنجاح');
    }
}
