<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingRequest;
use App\Models\Setting;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPricingRequestController extends Controller
{
    /**
     * عرض قائمة طلبات التسعير
     */
    public function index(Request $request)
    {
        $query = PricingRequest::with('customer', 'items');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('request_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($request) {
                      $customerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $pricingRequests = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pricing-requests.index', compact('pricingRequests'));
    }

    /**
     * عرض تفاصيل طلب تسعير
     */
    public function show($id)
    {
        $pricingRequest = PricingRequest::with('customer.user', 'items')->findOrFail($id);
        
        // إنشاء رابط واتساب
        $whatsappNumber = Setting::get('whatsapp_number', '201234567890');
        $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
        
        $message = "طلب تسعير جديد\n";
        $message .= "رقم الطلب: " . $pricingRequest->request_number . "\n";
        $message .= "العميل: " . $pricingRequest->customer->name . "\n";
        $message .= "الهاتف: " . $pricingRequest->customer->phone . "\n\n";
        $message .= "المنتجات:\n";
        
        foreach ($pricingRequest->items as $index => $item) {
            $message .= ($index + 1) . ". " . $item->product_name . " - الكمية: " . $item->quantity;
            if ($item->unit) {
                $message .= " " . $item->unit;
            }
            if ($item->notes) {
                $message .= " (" . $item->notes . ")";
            }
            $message .= "\n";
        }
        
        if ($pricingRequest->notes) {
            $message .= "\nملاحظات: " . $pricingRequest->notes;
        }
        
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return view('admin.pricing-requests.show', compact('pricingRequest', 'whatsappUrl'));
    }

    /**
     * تحديث الأسعار
     */
    public function updatePrices(Request $request, $id)
    {
        $pricingRequest = PricingRequest::with('customer.user', 'items')->findOrFail($id);

        if (!$pricingRequest->canBePriced()) {
            return redirect()->back()->with('error', 'لا يمكن تحديث الأسعار لهذا الطلب');
        }

        $validated = $request->validate([
            'prices' => 'required|array',
            'prices.*' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // تحديث الأسعار
            foreach ($validated['prices'] as $itemId => $price) {
                $item = $pricingRequest->items()->find($itemId);
                if ($item) {
                    $item->update(['price' => $price ?: null]);
                }
            }

            // تحديث ملاحظات الإدمن
            if (isset($validated['admin_notes'])) {
                $pricingRequest->update(['admin_notes' => $validated['admin_notes']]);
            }

            // تحديث حالة الطلب إلى priced
            $pricingRequest->update(['status' => 'priced']);

            // إرسال إشعار للعميل
            if ($pricingRequest->customer && $pricingRequest->customer->user) {
                NotificationHelper::success(
                    'تم تحديد أسعار طلب التسعير',
                    'تم تحديد أسعار طلب التسعير رقم ' . $pricingRequest->request_number . '، يمكنك مراجعته الآن',
                    $pricingRequest->customer->user->id,
                    route('pricing-requests.show', $pricingRequest->id)
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث الأسعار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الأسعار');
        }
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, $id)
    {
        $pricingRequest = PricingRequest::with('customer.user')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,priced,completed,cancelled',
        ]);

        $oldStatus = $pricingRequest->status;
        $pricingRequest->update(['status' => $validated['status']]);

        // إرسال إشعار للعميل عند تغيير الحالة
        if ($oldStatus != $validated['status'] && $pricingRequest->customer && $pricingRequest->customer->user) {
            $statusMessages = [
                'pending' => 'قيد الانتظار',
                'priced' => 'تم التسعير',
                'completed' => 'مكتمل',
                'cancelled' => 'ملغي'
            ];

            $statusTypes = [
                'pending' => 'info',
                'priced' => 'success',
                'completed' => 'success',
                'cancelled' => 'error'
            ];

            $message = 'تم تحديث حالة طلب التسعير رقم ' . $pricingRequest->request_number . ' إلى: ' . $statusMessages[$validated['status']];
            $type = $statusTypes[$validated['status']] ?? 'info';

            if ($type === 'success') {
                NotificationHelper::success(
                    'تحديث حالة طلب التسعير',
                    $message,
                    $pricingRequest->customer->user->id,
                    route('pricing-requests.show', $pricingRequest->id)
                );
            } elseif ($type === 'error') {
                NotificationHelper::error(
                    'تحديث حالة طلب التسعير',
                    $message,
                    $pricingRequest->customer->user->id,
                    route('pricing-requests.show', $pricingRequest->id)
                );
            } else {
                NotificationHelper::info(
                    'تحديث حالة طلب التسعير',
                    $message,
                    $pricingRequest->customer->user->id,
                    route('pricing-requests.show', $pricingRequest->id)
                );
            }
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}

