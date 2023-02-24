<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone'];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function notPaidPatients()
    {
        return $this->patients()->whereDoesntHave('payment');
    }
}
