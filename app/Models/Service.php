<?php

namespace App\Models;

use App\ServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name',
        'service_type',
        'price',
        'start_date_and_time',
        'end_date_and_time',
        'description',
        'duration',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'service_type' => ServiceType::class,
        'start_date_and_time' => 'datetime',
        'end_date_and_time' => 'datetime',
    ];
}
