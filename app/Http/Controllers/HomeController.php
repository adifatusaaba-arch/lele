<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::with(['category', 'primaryImage'])
            ->active()
            ->where('is_featured', true)
            ->latest()
            ->limit(8)
            ->get();

        $latestProducts = Product::with(['category', 'primaryImage'])
            ->active()
            ->latest()
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts', 'latestProducts'));
    }
}
