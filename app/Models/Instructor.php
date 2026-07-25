<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'languages',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'languages' => 'array',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'instructor_service');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(InstructorSchedule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
