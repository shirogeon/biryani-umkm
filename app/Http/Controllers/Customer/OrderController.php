<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Handle Customer Checkout Submission
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|min:8|max:20',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'table_or_address' => 'required|string|max:500',
            'payment_method' => 'required|in:qris,transfer,cod',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:50',
            'items.*.notes' => 'nullable|string|max:255',
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'order_type.required' => 'Silakan pilih tipe pesanan (Dine-in, Takeaway, atau Delivery).',
            'table_or_address.required' => 'Nomor meja atau alamat pengiriman wajib diisi.',
            'payment_method.required' => 'Pilih salah satu metode pembayaran.',
            'items.required' => 'Keranjang pesanan masih kosong.',
            'items.min' => 'Pilih minimal satu menu untuk dipesan.',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $order = DB::transaction(function () use ($request) {
                $shippingCost = 0;
                if ($request->order_type === 'delivery') {
                    $shippingCost = (float) StoreSetting::get('shipping_flat_rate', 10000);
                }

                $orderCode = Order::generateOrderCode();
                $totalAmount = 0;
                $itemsToCreate = [];

                foreach ($request->items as $itemData) {
                    $product = Product::findOrFail($itemData['id']);

                    if (!$product->is_available) {
                        throw new \Exception("Maaf, menu {$product->name} saat ini sedang habis.");
                    }

                    $quantity = (int) $itemData['quantity'];
                    $price = (float) $product->price;
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;

                    $itemsToCreate[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'notes' => $itemData['notes'] ?? null,
                    ];
                }

                $totalAmount += $shippingCost;

                $order = Order::create([
                    'order_code' => $orderCode,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'order_type' => $request->order_type,
                    'table_or_address' => $request->table_or_address,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->payment_method === 'cod' ? 'unpaid' : 'unpaid',
                    'order_status' => 'pending',
                    'shipping_cost' => $shippingCost,
                    'total_amount' => $totalAmount,
                    'notes' => $request->notes,
                ]);

                foreach ($itemsToCreate as $item) {
                    $order->items()->create($item);
                }

                return $order;
            });

            // Load items relationship
            $order->load('items');

            // Build WhatsApp Message for Store Admin
            $storePhone = StoreSetting::get('store_phone', '6281298765432');
            $waMessage = WhatsAppNotificationService::buildOrderSummary($order);
            $waUrl = WhatsAppNotificationService::generateUrl($storePhone, $waMessage);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pesanan berhasil dibuat!',
                    'order' => $order,
                    'whatsapp_url' => $waUrl,
                    'redirect_url' => route('order.success', ['code' => $order->order_code]),
                ], 201);
            }

            return redirect()->route('order.success', ['code' => $order->order_code]);

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 400);
            }
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Order Success Screen
     */
    public function success(Request $request, $code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();
        $storePhone = StoreSetting::get('store_phone', '6281298765432');
        $bankInfo = StoreSetting::get('bank_info', 'BCA 8735019281 a.n Dapur Biryani Berkah');
        $qrisMerchant = StoreSetting::get('qris_merchant_name', 'DAPUR BIRYANI BERKAH QRIS');

        $waMessage = WhatsAppNotificationService::buildOrderSummary($order);
        $waUrl = WhatsAppNotificationService::generateUrl($storePhone, $waMessage);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'order' => $order,
                'whatsapp_url' => $waUrl,
                'bank_info' => $bankInfo,
                'qris_merchant' => $qrisMerchant,
            ]);
        }

        return view('customer.order-success', compact('order', 'waUrl', 'bankInfo', 'qrisMerchant'));
    }

    /**
     * Track Order
     */
    public function track(Request $request, $code = null)
    {
        $search = $code ?: $request->input('query');
        $order = null;

        if (!empty($search)) {
            $order = Order::with('items')
                ->where('order_code', trim($search))
                ->orWhere('customer_phone', trim($search))
                ->latest()
                ->first();
        }

        if ($request->wantsJson()) {
            if (!$order) {
                return response()->json([
                    'status' => 'not_found',
                    'message' => 'Pesanan tidak ditemukan dengan nomor tersebut.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'order' => $order,
            ]);
        }

        return view('customer.track-order', compact('order', 'search'));
    }

    /**
     * View / Print Receipt
     */
    public function receipt(Request $request, $code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();
        $storeName = StoreSetting::get('store_name', 'Dapur Nasi Biryani Berkah');
        $storeAddress = StoreSetting::get('store_address', 'Jl. Aroma Rempah No. 88, Tebet, Jakarta');
        $storePhone = StoreSetting::get('store_phone', '6281298765432');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'order' => $order,
                'store' => [
                    'name' => $storeName,
                    'address' => $storeAddress,
                    'phone' => $storePhone,
                ],
            ]);
        }

        return view('customer.receipt', compact('order', 'storeName', 'storeAddress', 'storePhone'));
    }
}
