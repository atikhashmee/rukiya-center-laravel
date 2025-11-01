<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'service_id',
        'full_name',
        'email',
        'mother_name',
        'inquiry_description',
        'service_price',
        'price_type',
        'payment_status',
        'booking_status',
        'phone_number',
    ];

    protected $attributes = [
        'service_price' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Define the relationship: A booking belongs to a Customer.
     */
    public function customer(): BelongsTo
    {
        // Assumes you have a Customer model defined
        return $this->belongsTo(Customer::class);
    }

    /**
     * Helper scope to quickly find pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}
