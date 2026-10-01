<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'products' => $products,
                'categories' => $categories,
            ]);
        }

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200|unique:products,name',
            'description' => 'nullable|string|max:1000',
            'spiciness_level' => 'required|integer|min:0|max:5',
            'price' => 'required|numeric|min:0',
            'portion_size' => 'nullable|string|max:100',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url|max:500',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $imagePath = $request->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('products', $fileName, 'public');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'spiciness_level' => $validated['spiciness_level'],
            'price' => $validated['price'],
            'portion_size' => $validated['portion_size'] ?: '1 Porsi',
            'image' => $imagePath,
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil ditambahkan!',
                'product' => $product,
            ], 201);
        }

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200|unique:products,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'spiciness_level' => 'required|integer|min:0|max:5',
            'price' => 'required|numeric|min:0',
            'portion_size' => 'nullable|string|max:100',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url|max:500',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $imagePath = $product->image;
        if ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('products', $fileName, 'public');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'spiciness_level' => $validated['spiciness_level'],
            'price' => $validated['price'],
            'portion_size' => $validated['portion_size'] ?: '1 Porsi',
            'image' => $imagePath,
            'is_available' => $request->boolean('is_available'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil diperbarui!',
                'product' => $product,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->is_available = !$product->is_available;
        $product->save();

        return response()->json([
            'status' => 'success',
            'is_available' => $product->is_available,
            'message' => "Status ketersediaan {$product->name} berhasil diubah.",
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil dihapus.',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil dihapus.');
    }
}
