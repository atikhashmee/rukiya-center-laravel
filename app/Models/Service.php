<?php

namespace App\Models;

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
        'id_code',
        'category',
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
}
