<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $query = Order::with('items')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('order_status', ['completed', 'processing', 'delivering']);

        $orders = (clone $query)->latest()->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrdersCount = $orders->count();
        $averageOrderValue = $totalOrdersCount > 0 ? $totalRevenue / $totalOrdersCount : 0;

        $totalItemsSold = OrderItem::whereIn('order_id', $orders->pluck('id'))
            ->sum('quantity');

        // Best seller products during this period
        $topProducts = OrderItem::whereIn('order_id', $orders->pluck('id'))
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ],
                'summary' => [
                    'total_revenue' => $totalRevenue,
                    'total_orders' => $totalOrdersCount,
                    'average_order_value' => $averageOrderValue,
                    'total_items_sold' => $totalItemsSold,
                ],
                'top_products' => $topProducts,
                'orders' => $orders,
            ]);
        }

        return view('admin.reports.index', compact(
            'orders',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrdersCount',
            'averageOrderValue',
            'totalItemsSold',
            'topProducts'
        ));
    }
}
