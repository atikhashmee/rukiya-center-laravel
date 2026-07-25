<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_code',
        'category_id',
        'title',
        'tagline',
        'description',
        'icon',
        'card_color',
        'features',
        'order',
        'price_type',
        'price_value',
        'min_donation',
        'requires_custom_assessment',
        'required_form_fields',
        'submit_button_text',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'required_form_fields' => 'array',
            'price_value' => 'decimal:2',
            'min_donation' => 'decimal:2',
            'requires_custom_assessment' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class, 'instructor_service');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class);
    }
}
