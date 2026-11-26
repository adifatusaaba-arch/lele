<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $paidStatuses = ['dibayar', 'diproses', 'dikirim', 'selesai'];

        $ordersQuery = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', $paidStatuses);

        $totalRevenue = (float) (clone $ordersQuery)->sum('total_amount');
        $totalOrders = (clone $ordersQuery)->count();
        $averageOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $orders = $ordersQuery->latest()->paginate(20)->withQueryString();

        $topProducts = OrderItem::select('product_name', 'unit', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($q) use ($startDate, $endDate, $paidStatuses) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->whereIn('status', $paidStatuses);
            })
            ->groupBy('product_name', 'unit')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'averageOrder',
            'orders',
            'topProducts'
        ));
    }
}
