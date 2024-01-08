<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\PatientConsultation;
use Illuminate\Validation\ValidationException;

class PatientConsultationService
{
    public function store(array $data): PatientConsultation
    {
        $isRelatedToUser = Patient::where([
            'id' => $data['patient_id'],
            'share_to_user_id' => $data['user_id'],
        ])->exists();

        if (! $isRelatedToUser) {
            throw ValidationException::withMessages([
                'content' => 'Ошибка добавления',
            ]);
        }

        return PatientConsultation::create($data);
    }
}
