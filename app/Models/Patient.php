<?php

namespace App\Models;

use App\Models\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Patient extends Model
{
    use CurrentUser, HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'name',
        'medical_card_number',
        'birthday',
        'gender',
        'place_of_residence',
        'phone',
        'note',
        'comment',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function scopeFilter(Builder $q, array $data): void
    {
        $q->when(
            Arr::get($data, 'query'),
            fn ($q, $search) => $q->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('diagnosis', 'LIKE', '%'.$search.'%')
                ->orWhereRaw("JSON_SEARCH(case_numbers, 'all', ?) IS NOT NULL", ["%{$search}%"])
        );
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}
