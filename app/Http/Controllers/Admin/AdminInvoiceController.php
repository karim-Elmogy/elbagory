<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockMovement;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInvoiceController extends Controller
{
    // تم نقل الـ middleware إلى الرواتر

    public function index(Request $request)
    {
        $query = Invoice::with('customer', 'createdBy');

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($request) {
                      $customerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('type', 'wholesale')->where('status', 'active')->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit',
            'credit_days' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
        ]);

        // إنشاء رقم الفاتورة
        $lastInvoice = Invoice::whereYear('invoice_date', date('Y'))->orderBy('id', 'desc')->first();
        $invoiceNumber = 'WH-' . date('Y') . '-' . str_pad(($lastInvoice ? (int)substr($lastInvoice->invoice_number, -6) : 0) + 1, 6, '0', STR_PAD_LEFT);

        // حساب الإجماليات
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($validated['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = $itemTotal * ($item['discount_percentage'] ?? 0) / 100;
            $subtotal += $itemTotal;
            $totalDiscount += $itemDiscount;
        }

        $totalAfterDiscount = $subtotal - $totalDiscount;
        $tax = $validated['tax'] ?? ($totalAfterDiscount * 0.14); // ضريبة 14%
        $total = $totalAfterDiscount + $tax;

        // إنشاء الفاتورة
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $validated['invoice_date'],
            'customer_id' => $validated['customer_id'],
            'payment_method' => $validated['payment_method'],
            'credit_days' => $validated['credit_days'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'total_after_discount' => $totalAfterDiscount,
            'tax' => $tax,
            'total' => $total,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // إنشاء عناصر الفاتورة
        foreach ($validated['items'] as $index => $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = $itemTotal * ($item['discount_percentage'] ?? 0) / 100;
            $itemTotalAfterDiscount = $itemTotal - $itemDiscount;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_percentage' => $item['discount_percentage'] ?? 0,
                'discount_amount' => $itemDiscount,
                'total' => $itemTotalAfterDiscount,
                'sort_order' => $index,
            ]);

            // تحديث المخزون
            $product = Product::find($item['product_id']);
            if ($product) {
                $quantityBefore = $product->stock_quantity;
                $product->stock_quantity -= $item['quantity'];
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $product->stock_quantity,
                    'reference_type' => 'invoice',
                    'reference_id' => $invoice->id,
                    'notes' => 'فاتورة جملة رقم: ' . $invoice->invoice_number,
                    'created_by' => auth()->id(),
                ]);
            }
        }

        // إرسال إشعار للعميل عند إنشاء فاتورة
        $customer = Customer::with('user')->find($invoice->customer_id);
        if ($customer && $customer->user) {
            NotificationHelper::success(
                'فاتورة جديدة',
                'تم إنشاء فاتورة جديدة لك برقم: ' . $invoice->invoice_number . ' بقيمة: ' . number_format($invoice->total, 2) . ' ج.م',
                $customer->user->id,
                route('admin.invoices.show', $invoice->id)
            );
        }

        return redirect()->route('admin.invoices.show', $invoice->id)->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show($id)
    {
        $invoice = Invoice::with('customer', 'items.product', 'createdBy', 'payments')->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items.product')->findOrFail($id);
        
        if ($invoice->status == 'final') {
            return redirect()->back()->with('error', 'لا يمكن تعديل فاتورة نهائية');
        }

        $customers = Customer::where('type', 'wholesale')->where('status', 'active')->get();
        $products = Product::where('is_active', true)->get();
        
        return view('admin.invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status == 'final') {
            return redirect()->back()->with('error', 'لا يمكن تعديل فاتورة نهائية');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit',
            'credit_days' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
        ]);

        // حذف العناصر القديمة وإعادة المخزون
        foreach ($invoice->items as $oldItem) {
            $product = $oldItem->product;
            $product->stock_quantity += $oldItem->quantity;
            $product->save();
        }
        $invoice->items()->delete();

        // حساب الإجماليات الجديدة
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($validated['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = $itemTotal * ($item['discount_percentage'] ?? 0) / 100;
            $subtotal += $itemTotal;
            $totalDiscount += $itemDiscount;
        }

        $totalAfterDiscount = $subtotal - $totalDiscount;
        $tax = $validated['tax'] ?? ($totalAfterDiscount * 0.14);
        $total = $totalAfterDiscount + $tax;

        // تحديث الفاتورة
        $invoice->update([
            'invoice_date' => $validated['invoice_date'],
            'customer_id' => $validated['customer_id'],
            'payment_method' => $validated['payment_method'],
            'credit_days' => $validated['credit_days'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'total_after_discount' => $totalAfterDiscount,
            'tax' => $tax,
            'total' => $total,
        ]);

        // إنشاء العناصر الجديدة
        foreach ($validated['items'] as $index => $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemDiscount = $itemTotal * ($item['discount_percentage'] ?? 0) / 100;
            $itemTotalAfterDiscount = $itemTotal - $itemDiscount;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_percentage' => $item['discount_percentage'] ?? 0,
                'discount_amount' => $itemDiscount,
                'total' => $itemTotalAfterDiscount,
                'sort_order' => $index,
            ]);

            // تحديث المخزون
            $product = Product::find($item['product_id']);
            if ($product) {
                $quantityBefore = $product->stock_quantity;
                $product->stock_quantity -= $item['quantity'];
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $product->stock_quantity,
                    'reference_type' => 'invoice',
                    'reference_id' => $invoice->id,
                    'notes' => 'فاتورة جملة رقم: ' . $invoice->invoice_number,
                    'created_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('admin.invoices.show', $invoice->id)->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    public function cancel(Request $request, $id)
    {
        $invoice = Invoice::with('customer.user')->findOrFail($id);

        if ($invoice->status == 'cancelled') {
            return redirect()->back()->with('error', 'الفاتورة ملغاة بالفعل');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        // إعادة المخزون
        foreach ($invoice->items as $item) {
            $product = $item->product;
            $product->stock_quantity += $item->quantity;
            $product->save();
        }

        $invoice->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        // إرسال إشعار للعميل عند إلغاء الفاتورة
        if ($invoice->customer && $invoice->customer->user) {
            NotificationHelper::warning(
                'تم إلغاء الفاتورة',
                'تم إلغاء فاتورتك رقم: ' . $invoice->invoice_number . '. السبب: ' . $validated['cancellation_reason'],
                $invoice->customer->user->id,
                route('admin.invoices.show', $invoice->id)
            );
        }

        return redirect()->back()->with('success', 'تم إلغاء الفاتورة بنجاح');
    }

    public function print($id)
    {
        $invoice = Invoice::with('customer', 'items.product', 'createdBy')->findOrFail($id);
        return view('admin.invoices.print', compact('invoice'));
    }

    public function pdf($id)
    {
        // يمكن إضافة مكتبة PDF هنا مثل dompdf أو barryvdh/laravel-dompdf
        // للآن سنعيد إلى صفحة الطباعة
        return redirect()->route('admin.invoices.print', $id);
    }
}
