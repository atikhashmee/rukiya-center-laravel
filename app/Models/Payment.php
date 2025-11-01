<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'customer_id',
        'payment_intent_id',
        'payment_method_id',
        'currency',
        'amount',
        'status',
        'description',
        'order_id',
        'metadata',
        'response_payload',
    ];

    protected $casts = [
        'metadata' => 'array',
        'response_payload' => 'array',
    ];

    // Relation: each payment belongs to a user
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Helper: convert cents → readable amount
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount / 100, 2);
    }
}
