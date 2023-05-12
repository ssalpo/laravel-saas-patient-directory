<?php

namespace App\Models;

use App\Models\Traits\CurrentUser;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, CurrentUser, SoftDeletes;

    protected $fillable = [
        'hashid',
        'case_numbers',
        'status',
        'name',
        'phone',
        'birthday',
        'gender',
        'sampling_date',
        'sample_receipt_date',
        'anamnes',
        'categories',
        'doctor_id',
        'microscopic_description',
        'diagnosis',
        'note',
        'print_date',
        'created_by',
        'comment',
        'uniq_code'
    ];

    protected $casts = [
        'case_numbers' => 'array',
        'categories' => 'array',
        'birthday' => 'date',
        'print_date' => 'date',
        'sampling_date' => 'datetime',
        'sample_receipt_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (Patient $patient) {
            $patient->update(['hashid' => (new Hashids())->encode($patient->id)]);
        });
    }

    public const STATUS_CHECKING = 1;
    public const STATUS_CHECKED = 2;

    public function scopeFilter($q)
    {
        $q->when(
            request('query'),
            fn($q, $search) => $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('diagnosis', 'LIKE', '%' . $search . '%')
                ->orWhereRaw("JSON_SEARCH(case_numbers, 'all', ?) IS NOT NULL", ["%{$search}%"])
        );

        $q->when(
            request('status'),
            fn($q, $status) => $q->whereStatus($status)
        );
    }

    public function scopeMyByPermission($q, string $field = 'created_by')
    {
        $q->when(
            !auth()->user()?->can('read_all_patients'),
            fn($q) => $q->my($field)
        );
    }

    public function getCategoriesFormattedAttribute()
    {
        return array_map(function ($c) {
            $biopsy = $c['biopsyCustomValue'] ?? $c['biopsy'] ?? '';

            return sprintf('%s (%s)', $c['code'], ($biopsy ? $biopsy . ', ' : '') . $c['description']);
        }, $this->categories);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function generateCaseNumbers(): array
    {
        $caseNumbers = [];

        foreach ($this->categories as $category) {
            $caseNumbers[] = sprintf(
                'D%s/%s %s',
                substr(date('Y'), -2),
                sprintf("%02d", $this->id),
                $category['code']
            );
        }

        $this->update(['case_numbers' => $caseNumbers]);

        return $caseNumbers;
    }

    public function generateUniqCode(): int
    {
        do {
            $code = random_int(1000, 20000);
        } while (self::where("uniq_code", $code)->exists());

        $this->update(['uniq_code' => $code]);

        return $code;
    }
}
