<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'unit',
        'fish_size',
        'min_order',
        'stock',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'min_order' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getThumbnailUrlAttribute(): string
    {
        $image = $this->primaryImage ?? $this->images->first();
        if ($image && $image->image_path) {
            if (str_starts_with($image->image_path, 'http') || str_starts_with($image->image_path, '/')) {
                return $image->image_path;
            }
            return asset('storage/' . $image->image_path);
        }

        return asset('images/default-lele.svg');
    }
}
