<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'region',
        'area',
    ];

    protected $appends = [
        'full_address',
    ];

    public function fullAddress(): Attribute
    {
        return Attribute::get(fn () => implode(', ', [$this->region, $this->area]));
    }
}
