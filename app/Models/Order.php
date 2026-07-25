<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'email', 'full_name', 'phone',
        'subtotal', 'total', 'status', 'payment_status',
        'billing_address', 'notes',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'subtotal' => 'float',
        'total' => 'float',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }
}
