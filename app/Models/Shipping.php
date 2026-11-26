<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    public const METHODS = [
        'kurir_toko' => ['label' => 'Kurir Kolam Pak Dedi (Area Lokal)', 'cost' => 15000, 'estimate' => 'Same Day / 1 Hari'],
        'ekspedisi_oksigen' => ['label' => 'Ekspedisi Khusus Ikan Hidup (Oksigen)', 'cost' => 35000, 'estimate' => '1 - 2 Hari'],
        'ambil_sendiri' => ['label' => 'Ambil Langsung di Kolam Pak Dedi', 'cost' => 0, 'estimate' => 'Siap di Hari yang Sama'],
    ];

    public const STATUS_LABELS = [
        'pending' => 'Menunggu',
        'disiapkan' => 'Sedang Disiapkan',
        'dikirim' => 'Dalam Pengiriman',
        'diterima' => 'Diterima',
    ];

    protected $fillable = [
        'order_id',
        'shipping_method',
        'courier_name',
        'tracking_number',
        'cost',
        'estimated_delivery',
        'status',
        'shipped_at',
        'delivered_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->shipping_method]['label'] ?? ucfirst(str_replace('_', ' ', $this->shipping_method));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }
}
