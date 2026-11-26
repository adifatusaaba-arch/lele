<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const METHODS = [
        'transfer_bca' => 'Transfer Bank BCA',
        'transfer_bri' => 'Transfer Bank BRI',
        'transfer_mandiri' => 'Transfer Bank Mandiri',
        'qris' => 'QRIS',
        'cod' => 'Bayar di Tempat (COD)',
    ];

    public const STATUS_LABELS = [
        'pending' => 'Belum Bayar',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
    ];

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'bank_name',
        'account_name',
        'account_number',
        'proof_image',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getProofUrlAttribute(): ?string
    {
        if (! $this->proof_image) {
            return null;
        }
        return asset('storage/' . $this->proof_image);
    }
}
