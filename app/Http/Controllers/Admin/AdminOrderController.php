<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // تم نقل الـ middleware إلى الرواتر

    public function index(Request $request)
    {
        $query = Order::with('customer', 'items.product');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($request) {
                      $customerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('customer', 'items.product', 'createdBy')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('customer.user')->findOrFail($id);
        $oldStatus = $order->status;

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        // إرسال إشعار للمستخدم عند تغيير حالة الطلب
        if ($oldStatus != $validated['status'] && $order->customer && $order->customer->user) {
            $statusMessages = [
                'pending' => 'قيد الانتظار',
                'processing' => 'قيد المعالجة',
                'shipped' => 'تم الشحن',
                'delivered' => 'تم التسليم',
                'cancelled' => 'ملغي'
            ];

            $statusTypes = [
                'pending' => 'info',
                'processing' => 'warning',
                'shipped' => 'info',
                'delivered' => 'success',
                'cancelled' => 'error'
            ];

            $message = 'تم تحديث حالة طلبك رقم ' . $order->order_number . ' إلى: ' . $statusMessages[$validated['status']];
            $type = $statusTypes[$validated['status']] ?? 'info';

            // استخدام الـ helper المناسب حسب النوع
            if ($type === 'success') {
                NotificationHelper::success(
                    'تحديث حالة الطلب',
                    $message,
                    $order->customer->user->id,
                    route('orders.show', $order->id)
                );
            } elseif ($type === 'error') {
                NotificationHelper::error(
                    'تحديث حالة الطلب',
                    $message,
                    $order->customer->user->id,
                    route('orders.show', $order->id)
                );
            } elseif ($type === 'warning') {
                NotificationHelper::warning(
                    'تحديث حالة الطلب',
                    $message,
                    $order->customer->user->id,
                    route('orders.show', $order->id)
                );
            } else {
                NotificationHelper::info(
                    'تحديث حالة الطلب',
                    $message,
                    $order->customer->user->id,
                    route('orders.show', $order->id)
                );
            }
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
