<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'pending' => 'Pending',
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'dibayar' => 'Dibayar',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public const STATUS_BADGES = [
        'pending' => 'secondary',
        'menunggu_pembayaran' => 'warning text-dark',
        'menunggu_verifikasi' => 'info text-dark',
        'dibayar' => 'primary',
        'diproses' => 'primary',
        'dikirim' => 'info text-dark',
        'selesai' => 'success',
        'dibatalkan' => 'danger',
    ];

    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'shipping_recipient',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'status',
        'stock_deducted',
        'notes',
        'paid_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'stock_deducted' => 'boolean',
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(Shipping::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'secondary';
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'LPD-' . now()->format('Ymd') . '-';
        $latest = self::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('order_number');

        $nextNumber = $latest ? ((int) substr($latest, -4)) + 1 : 1;

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
