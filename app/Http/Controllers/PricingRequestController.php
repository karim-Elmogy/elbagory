<?php

namespace App\Http\Controllers;

use App\Models\PricingRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PricingRequestController extends Controller
{
    /**
     * عرض صفحة إنشاء طلب تسعير
     */
    public function create()
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer || !$customer->isWholesale()) {
            return redirect()->route('customer.account')->with('error', 'هذه الميزة متاحة لعملاء الجملة فقط');
        }

        return view('pricing-requests.create');
    }

    /**
     * حفظ طلب تسعير جديد
     */
    public function store(Request $request)
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer || !$customer->isWholesale()) {
            return redirect()->route('customer.account')->with('error', 'هذه الميزة متاحة لعملاء الجملة فقط');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.notes' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'items.required' => 'يجب إضافة منتج واحد على الأقل',
            'items.min' => 'يجب إضافة منتج واحد على الأقل',
            'items.*.product_name.required' => 'اسم المنتج مطلوب',
            'items.*.quantity.required' => 'الكمية مطلوبة',
            'items.*.quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
        ]);

        DB::beginTransaction();
        try {
            $pricingRequest = PricingRequest::create([
                'customer_id' => $customer->id,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $pricingRequest->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // إنشاء إشعار للإدمن
            $adminUsers = \App\Models\User::whereHas('role', function($query) {
                $query->where('slug', 'admin');
            })->get();

            foreach ($adminUsers as $admin) {
                \App\Helpers\NotificationHelper::info(
                    'طلب تسعير جديد',
                    'تم إنشاء طلب تسعير جديد رقم ' . $pricingRequest->request_number . ' من ' . $customer->name,
                    $admin->id,
                    route('admin.pricing-requests.show', $pricingRequest->id)
                );
            }

            DB::commit();

            return redirect()->route('pricing-requests.show', $pricingRequest->id)
                ->with('success', 'تم إنشاء طلب التسعير بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء طلب التسعير');
        }
    }

    /**
     * عرض قائمة طلبات التسعير للعميل
     */
    public function index()
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer || !$customer->isWholesale()) {
            return redirect()->route('customer.account')->with('error', 'هذه الميزة متاحة لعملاء الجملة فقط');
        }

        $pricingRequests = PricingRequest::where('customer_id', $customer->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pricing-requests.index', compact('pricingRequests'));
    }

    /**
     * عرض تفاصيل طلب تسعير
     */
    public function show($id)
    {
        $customer = auth()->user()->customers()->first();
        
        if (!$customer || !$customer->isWholesale()) {
            return redirect()->route('customer.account')->with('error', 'هذه الميزة متاحة لعملاء الجملة فقط');
        }

        $pricingRequest = PricingRequest::where('customer_id', $customer->id)
            ->with('items', 'customer')
            ->findOrFail($id);

        return view('pricing-requests.show', compact('pricingRequest'));
    }
}

