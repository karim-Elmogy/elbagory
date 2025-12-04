<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    // تم نقل الـ middleware إلى الرواتر

    public function index(Request $request)
    {
        $query = Customer::with('user');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with('user', 'orders', 'invoices')->findOrFail($id);
        
        $totalOrders = $customer->orders()->count();
        $totalInvoices = $customer->invoices()->count();
        $totalSpent = $customer->orders()->sum('total') + $customer->invoices()->sum('total');
        
        return view('admin.customers.show', compact('customer', 'totalOrders', 'totalInvoices', 'totalSpent'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone,' . $id,
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'type' => 'required|in:retail,wholesale',
            'company_name' => 'nullable|string',
            'tax_number' => 'nullable|string',
            'address' => 'nullable|string',
            'detailed_address' => 'nullable|string',
            'preferred_payment_method' => 'nullable|in:cash,bank_transfer,credit',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,suspended',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'تم تحديث بيانات العميل بنجاح');
    }
}
