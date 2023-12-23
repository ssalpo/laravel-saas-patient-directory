<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone'];

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function notPaidPatients(): HasMany
    {
        return $this->patients()->whereDoesntHave('payment');
    }

    public function paidPatients(): HasMany
    {
        return $this->patients()->whereHas('payment');
    }
}
