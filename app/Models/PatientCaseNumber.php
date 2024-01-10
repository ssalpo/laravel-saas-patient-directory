<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientCaseNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'year',
        'number',
        'code',
        'formatted',
    ];

    public const BASE_PREFIX = 'D';

    public function patent(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public static function currentYearMaxNumber()
    {
        return self::where('year', date('Y'))->max('number');
    }

    public static function hasAnyDiffInStructure(int $patientId, int $year, array $codes): bool
    {
        $storedCodes = self::where(['year' => $year, 'patient_id' => $patientId])->pluck('code')->toArray();

        return $storedCodes !== $codes;
    }
}
