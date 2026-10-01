<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StoreSetting;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('order_type') && $request->order_type !== 'all') {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('order_code', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'delivering' => Order::where('order_status', 'delivering')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'orders' => $orders,
                'counts' => $counts,
            ]);
        }

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        $waCustomerMessage = WhatsAppNotificationService::buildCustomerStatusUpdateMessage($order);
        $waCustomerUrl = WhatsAppNotificationService::generateUrl($order->customer_phone, $waCustomerMessage);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'order' => $order,
                'whatsapp_customer_url' => $waCustomerUrl,
            ]);
        }

        return view('admin.orders.show', compact('order', 'waCustomerUrl'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'order_status' => 'required|in:pending,processing,delivering,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid',
        ]);

        $order->order_status = $request->order_status;

        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        if ($request->order_status === 'completed' && !$order->completed_at) {
            $order->completed_at = now();
            $order->payment_status = 'paid';
        }

        $order->save();

        $waMessage = WhatsAppNotificationService::buildCustomerStatusUpdateMessage($order);
        $waUrl = WhatsAppNotificationService::generateUrl($order->customer_phone, $waMessage);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status pesanan berhasil diperbarui.',
                'order' => $order,
                'whatsapp_url' => $waUrl,
            ]);
        }

        return back()->with('success', "Status pesanan {$order->order_code} berhasil diubah menjadi {$order->status_label}.");
    }

    public function receipt($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $storeName = StoreSetting::get('store_name', 'Dapur Nasi Biryani Berkah');
        $storeAddress = StoreSetting::get('store_address', 'Jl. Aroma Rempah No. 88, Tebet, Jakarta');
        $storePhone = StoreSetting::get('store_phone', '6281298765432');

        return view('admin.orders.receipt', compact('order', 'storeName', 'storeAddress', 'storePhone'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
