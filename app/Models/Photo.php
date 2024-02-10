<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['label', 'url', 'has_thumb'];

    protected $casts = [
        'has_thumb' => 'boolean',
    ];

    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }
}
