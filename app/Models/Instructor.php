<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRegion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use BelongsToRegion;

    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'email',
        'phone',
        'bio',
        'languages',
        'experience',
        'location',
        'appointment_type',
        'photo',
        'is_active',
        'region',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'languages' => 'array',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'instructor_service');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
