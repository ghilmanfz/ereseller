<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'recipient_name',
        'recipient_whatsapp',
        'recipient_address',
        'shipping_method',
        'payment_method',
        'status',
        'tracking_number',
        'payment_proof_path',
        'subtotal',
        'shipping_cost',
        'total',
        'payment_proof_uploaded_at',
        'paid_at',
        'processing_at',
        'shipped_at',
        'ready_for_pickup_at',
        'pickup_ready_reminded_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'payment_proof_uploaded_at' => 'datetime',
            'paid_at' => 'datetime',
            'processing_at' => 'datetime',
            'shipped_at' => 'datetime',
            'ready_for_pickup_at' => 'datetime',
            'pickup_ready_reminded_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
