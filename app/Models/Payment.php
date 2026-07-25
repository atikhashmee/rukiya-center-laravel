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
        'order_type', // model name
        'metadata',
        'response_payload',
    ];

    protected $casts = [
        'metadata' => 'array',
        'response_payload' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payable()
    {
        return $this->morphTo('order', 'order_type', 'order_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'order_id');
    }

    // Helper: convert cents → readable amount
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount / 100, 2);
    }
}
