<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreSetting;

class WhatsAppNotificationService
{
    /**
     * Format WhatsApp message for order confirmation to admin or customer
     */
    public static function buildOrderSummary(Order $order): string
    {
        $storeName = StoreSetting::get('store_name', 'Dapur Nasi Biryani Berkah');
        $itemsText = "";

        foreach ($order->items as $idx => $item) {
            $num = $idx + 1;
            $itemsText .= "{$num}. {$item->product_name} x{$item->quantity} = Rp " . number_format($item->subtotal, 0, ',', '.') . "\n";
            if (!empty($item->notes)) {
                $itemsText .= "   Catatan: {$item->notes}\n";
            }
        }

        $typeLabel = $order->order_type_label;
        $locationLabel = match ($order->order_type) {
            'dine_in' => "No. Meja: {$order->table_or_address}",
            'takeaway' => "Catatan Ambil: {$order->table_or_address}",
            'delivery' => "Alamat Pengiriman: {$order->table_or_address}",
        };

        $shippingText = "";
        if ($order->shipping_cost > 0) {
            $shippingText = "Ongkos Kirim: Rp " . number_format($order->shipping_cost, 0, ',', '.') . "\n";
        }

        $notesText = !empty($order->notes) ? "\nCatatan Tambahan: {$order->notes}\n" : "";

        $message = "Halo *{$storeName}*,\n\n"
            . "Saya ingin mengonfirmasi pesanan baru:\n"
            . "------------------------------------\n"
            . "*No. Pesanan*: {$order->order_code}\n"
            . "*Nama*: {$order->customer_name}\n"
            . "*No. HP*: {$order->customer_phone}\n"
            . "*Tipe Order*: {$typeLabel}\n"
            . "{$locationLabel}\n"
            . "*Metode Bayar*: {$order->payment_method_label}\n"
            . "*Status Bayar*: " . strtoupper($order->payment_status) . "\n"
            . "------------------------------------\n"
            . "*Rincian Pesanan*:\n"
            . "{$itemsText}"
            . "{$shippingText}"
            . "*Total Pembayaran*: *{$order->formatted_total}*\n"
            . "{$notesText}"
            . "------------------------------------\n"
            . "Terima kasih banyak!";

        return $message;
    }

    /**
     * Format WhatsApp notification from Admin to Customer on status update
     */
    public static function buildCustomerStatusUpdateMessage(Order $order): string
    {
        $storeName = StoreSetting::get('store_name', 'Dapur Nasi Biryani Berkah');
        $statusMessage = match ($order->order_status) {
            'processing' => "Pesanan Anda *{$order->order_code}* saat ini *sedang dimasak & dipersiapkan* oleh tim dapur kami dengan bumbu rempah pilihan.",
            'delivering' => $order->order_type === 'delivery' 
                ? "Kabar baik! Pesanan Anda *{$order->order_code}* *sedang dalam perjalanan pengiriman* ke alamat Anda."
                : "Pesanan Anda *{$order->order_code}* *sudah siap disajikan / diambil* di kasir.",
            'completed' => "Pesanan Anda *{$order->order_code}* telah *SELESAI*. Terima kasih telah menikmati sajian Nasi Biryani kami! Semoga berkah dan cocok dengan selera Anda.",
            'cancelled' => "Mohon maaf, pesanan Anda *{$order->order_code}* terpaksa *dibatalkan*. Silakan hubungi kami untuk informasi lebih lanjut.",
            default => "Update status pesanan Anda *{$order->order_code}*: *{$order->status_label}*.",
        };

        $message = "Halo Kak *{$order->customer_name}*,\n\n"
            . "Salam hangat dari *{$storeName}*!\n\n"
            . "{$statusMessage}\n\n"
            . "Total Pesanan: *{$order->formatted_total}*\n"
            . "Metode: {$order->order_type_label}\n\n"
            . "Jika ada pertanyaan atau bantuan, silakan balas pesan ini ya Kak.\n"
            . "Selamat menikmati!";

        return $message;
    }

    /**
     * Generate clickable WhatsApp link
     */
    public static function generateUrl(string $phone, string $message): string
    {
        // Sanitize phone number (replace leading 0 or + with 62)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        return "https://wa.me/{$cleanPhone}?text=" . rawurlencode($message);
    }
}
