<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'name',
        'email',
        'comment',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
