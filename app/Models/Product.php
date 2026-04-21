<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image_url',
        'description',
        'price',
        'compare_price',
        'rating',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockStatus(): string
    {
        if ($this->stock <= 0) {
            return 'Out-of-Stock';
        }

        if ($this->stock <= 10) {
            return 'Limited';
        }

        return 'In-Stock';
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }
}
