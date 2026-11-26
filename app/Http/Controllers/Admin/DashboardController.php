<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['dibayar', 'diproses', 'dikirim', 'selesai'])->sum('total_amount');

        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->limit(6)
            ->get();

        // 7-day sales chart data
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[] = (float) Order::whereDate('created_at', $date)
                ->whereIn('status', ['dibayar', 'diproses', 'dikirim', 'selesai'])
                ->sum('total_amount');
        }

        $lowStockProducts = Product::where('stock', '<=', 20)->orderBy('stock')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'chartLabels',
            'chartData',
            'lowStockProducts'
        ));
    }
}
