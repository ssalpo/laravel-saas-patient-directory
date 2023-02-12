<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_numbers',
        'name',
        'birthday',
        'gender',
        'sampling_date',
        'sample_receipt_date',
        'anamnes',
        'doctor_id',
        'microscopic_description',
        'diagnosis',
        'note',
    ];

    protected $casts = [
        'case_numbers' => 'array',
        'birthday' => 'date',
        'sampling_date' => 'datetime',
        'sample_receipt_date' => 'datetime',
    ];

    public function scopeFilter($q)
    {
        $q->when(
            request('query'),
            fn($q, $search) => $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereRaw("JSON_SEARCH(case_numbers, 'all', ?) IS NOT NULL", ["%{$search}%"])
        );
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function generateCaseNumbers(): array
    {
        $caseNumbers = [];

        foreach ($this->categories as $category) {
            $caseNumbers[] = sprintf(
                'D%s/%s %s',
                substr(date('Y'), -2),
                sprintf("%02d", $this->id),
                $category->name
            );
        }

        $this->update(['case_numbers' => $caseNumbers]);

        return $caseNumbers;
    }
}
