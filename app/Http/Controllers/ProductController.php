<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != 0) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('category')->first();
        
        // Fallback: إذا لم يتم العثور على المنتج بالـ slug، جرب البحث بالـ id
        if (!$product && is_numeric($slug)) {
            $product = Product::with('category')->find($slug);
            if ($product && $product->slug) {
                return redirect()->route('products.show', $product->slug, 301);
            }
        }
        
        if (!$product) {
            abort(404);
        }
        
        // التأكد من وجود slug
        if (empty($product->slug)) {
            $product->slug = \Illuminate\Support\Str::slug($product->name);
            $product->save();
        }
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category')
            ->paginate(12);

        return view('products.category', compact('category', 'products'));
    }
}
