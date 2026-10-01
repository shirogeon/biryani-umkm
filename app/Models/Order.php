<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'order_type',
        'table_or_address',
        'payment_method',
        'payment_status',
        'order_status',
        'total_amount',
        'shipping_cost',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'shipping_cost' => 'float',
        'completed_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_total',
        'status_label',
        'order_type_label',
        'payment_method_label',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'Menunggu Konfirmasi',
            'processing' => 'Sedang Dimasak',
            'delivering' => $this->order_type === 'delivery' ? 'Sedang Diantar' : 'Siap Diambil/Disajikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->order_status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
            'delivering' => 'bg-purple-100 text-purple-800 border-purple-300',
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function getOrderTypeLabelAttribute(): string
    {
        return match ($this->order_type) {
            'dine_in' => 'Makan di Tempat (Dine-in)',
            'takeaway' => 'Bawa Pulang (Takeaway)',
            'delivery' => 'Pesan Antar (Delivery)',
            default => ucfirst($this->order_type),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS / E-Wallet',
            'transfer' => 'Transfer Bank',
            'cod' => 'Bayar di Tempat (COD / Kasir)',
            default => strtoupper($this->payment_method),
        };
    }

    public static function generateOrderCode(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "BRY-{$date}-{$random}";
    }
}
