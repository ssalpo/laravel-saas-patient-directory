<?php

namespace App\Models;

use App\Models\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Patient extends Model
{
    use CurrentUser, HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'share_to_user_id',
        'is_share_notification_viewed',
        'name',
        'medical_card_number',
        'birthday',
        'gender',
        'place_of_residence',
        'phone',
        'note',
        'comment',
        'morbi',
        'vitae',
        'lab_workup',
        'diagnosis',
        'mkb',
        'treatment',
    ];

    protected $casts = [
        'is_share_notification_viewed' => 'bool',
        'birthday' => 'date',
    ];

    public function scopeFilter(Builder $q, array $data): void
    {
        $q->when(
            Arr::get($data, 'query'),
            fn ($q, $search) => $q->where('name', 'LIKE', '%'.$search.'%')
        );
    }

    public function scopeSharedToMe(Builder $q, int $sharedUserId): void
    {
        $q->where('share_to_user_id', $sharedUserId)
            ->orWhereHas('currentUserConsultations');
    }

    public function scopeMyOrSharedWithMe(Builder $q, int $sharedUserId, string $currentUserField = 'user_id'): void
    {
        $q->my($currentUserField)
            ->orWhere('share_to_user_id', $sharedUserId)
            ->orWhereHas('currentUserConsultations');
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public static function sharedWithMeCount(?int $currentUserId): int
    {
        if (is_null($currentUserId)) {
            return 0;
        }

        return self::sharedToMe($currentUserId)
            ->where('is_share_notification_viewed', false)
            ->count();
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(PatientConsultation::class);
    }

    public function currentUserConsultations(): HasMany
    {
        return $this->consultations()->where('user_id', auth()->id());
    }
}
