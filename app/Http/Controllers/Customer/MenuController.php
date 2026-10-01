<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Get list of categories with their available products
     */
    public function index(Request $request)
    {
        $categories = Category::active()
            ->with(['products' => function ($query) {
                $query->available()->orderBy('is_featured', 'desc');
            }])
            ->get();

        $featuredProducts = Product::available()->featured()->take(4)->get();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'categories' => $categories,
                'featured' => $featuredProducts,
            ]);
        }

        return view('customer.menu', compact('categories', 'featuredProducts'));
    }

    /**
     * Get specific product detail
     */
    public function show(Request $request, $slug)
    {
        $product = Product::available()->where('slug', $slug)->firstOrFail();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'product' => $product,
            ]);
        }

        return view('customer.product-detail', compact('product'));
    }
}
