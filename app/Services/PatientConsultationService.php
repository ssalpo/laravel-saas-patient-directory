<?php

namespace App\Services;

use App\Models\PatientConsultation;

class PatientConsultationService
{
    public function store(array $data): PatientConsultation
    {
        return PatientConsultation::create($data);
    }
}
