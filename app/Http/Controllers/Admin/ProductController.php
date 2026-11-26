<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:kg,ekor'],
            'fish_size' => ['nullable', 'string', 'max:100'],
            'min_order' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::lower(Str::random(4));
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'fish_size' => $validated['fish_size'] ?? null,
            'min_order' => $validated['min_order'],
            'stock' => $validated['stock'],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load('images');
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:kg,ekor'],
            'fish_size' => ['nullable', 'string', 'max:100'],
            'min_order' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'fish_size' => $validated['fish_size'] ?? null,
            'min_order' => $validated['min_order'],
            'stock' => $validated['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if ($request->hasFile('images')) {
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            $maxOrder = (int) $product->images()->max('sort_order');

            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => ! $hasPrimary && $index === 0,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $img) {
            if (! str_starts_with($img->image_path, '/') && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        if (! str_starts_with($image->image_path, '/') && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $next = $product->images()->first();
            $next?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Foto produk berhasil dihapus.');
    }
}
