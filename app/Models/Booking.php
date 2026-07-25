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
        'instructor_id',
        'booking_date',
        'booking_time',
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone_number',
        'phone_country',
        'mother_name',
        'inquiry_description',
        'gender',
        'language',
        'ethnic_origin',
        'age',
        'is_first_appointment',
        'symptoms',
        'symptoms_other',
        'found_via',
        'consent_updates',
        'guardian_gender',
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'service_price',
        'price_type',
        'payment_status',
        'booking_status',
    ];

    protected $attributes = [
        'service_price' => 'decimal:2',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'symptoms' => 'array',
        'found_via' => 'array',
        'consent_updates' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}
