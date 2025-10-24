<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'author_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'post_id')->where('approved', true);
    }
}
