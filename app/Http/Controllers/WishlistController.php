<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول لعرض المفضلة');
        }

        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.category')
            ->latest()
            ->paginate(20);

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request, $slug)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تسجيل الدخول أولاً',
                'redirect' => route('login')
            ], 401);
        }

        $product = Product::where('slug', $slug)->first();
        
        // Fallback: إذا لم يتم العثور على المنتج بالـ slug، جرب البحث بالـ id
        if (!$product && is_numeric($slug)) {
            $product = Product::find($slug);
            if ($product && $product->slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى المحاولة مرة أخرى',
                    'redirect' => route('wishlist.toggle', $product->slug)
                ], 302);
            }
        }
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود'
            ], 404);
        }
        
        // التأكد من وجود slug
        if (empty($product->slug)) {
            $product->slug = \Illuminate\Support\Str::slug($product->name);
            $product->save();
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $isInWishlist = false;
            $message = 'تم حذف المنتج من المفضلة';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $isInWishlist = true;
            $message = 'تم إضافة المنتج للمفضلة';
        }

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'isInWishlist' => $isInWishlist,
                'wishlistCount' => $wishlistCount
            ]);
        }

        return back()->with('success', $message);
    }

    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlist->delete();

        return back()->with('success', 'تم حذف المنتج من المفضلة');
    }
}
