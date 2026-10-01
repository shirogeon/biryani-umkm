<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Key Performance Indicators (KPIs)
        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)
            ->whereIn('order_status', ['completed', 'processing', 'delivering'])
            ->sum('total_amount');

        $monthOrdersCount = Order::where('created_at', '>=', $thisMonth)->count();
        $monthRevenue = Order::where('created_at', '>=', $thisMonth)
            ->whereIn('order_status', ['completed', 'processing', 'delivering'])
            ->sum('total_amount');

        $pendingOrdersCount = Order::where('order_status', 'pending')->count();
        $processingOrdersCount = Order::where('order_status', 'processing')->count();
        $deliveringOrdersCount = Order::where('order_status', 'delivering')->count();
        $completedOrdersCount = Order::where('order_status', 'completed')->count();

        // Recent Orders
        $recentOrders = Order::with('items')
            ->latest()
            ->take(6)
            ->get();

        // Top 5 Best Sellers
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 7 Days Sales Trend
        $salesTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayRevenue = Order::whereDate('created_at', $date)
                ->whereIn('order_status', ['completed', 'processing', 'delivering'])
                ->sum('total_amount');
            $dayOrders = Order::whereDate('created_at', $date)->count();

            $salesTrend[] = [
                'date' => $date->format('d M'),
                'day_name' => $date->isoFormat('ddd'),
                'revenue' => (float) $dayRevenue,
                'orders' => $dayOrders,
            ];
        }

        $totalProductsCount = Product::count();
        $availableProductsCount = Product::where('is_available', true)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'kpi' => [
                    'today_revenue' => $todayRevenue,
                    'today_orders' => $todayOrdersCount,
                    'month_revenue' => $monthRevenue,
                    'month_orders' => $monthOrdersCount,
                    'pending_orders' => $pendingOrdersCount,
                    'processing_orders' => $processingOrdersCount,
                    'completed_orders' => $completedOrdersCount,
                ],
                'sales_trend' => $salesTrend,
                'top_products' => $topProducts,
                'recent_orders' => $recentOrders,
            ]);
        }

        return view('admin.dashboard', compact(
            'todayRevenue',
            'todayOrdersCount',
            'monthRevenue',
            'monthOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'deliveringOrdersCount',
            'completedOrdersCount',
            'recentOrders',
            'topProducts',
            'salesTrend',
            'totalProductsCount',
            'availableProductsCount'
        ));
    }
}
