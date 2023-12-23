<?php

namespace App\Services;

use App\Models\MedicalClinic;

class MedicalClinicService
{
    /**
     * Добавляет новое медицинское учреждение
     */
    public function store(array $data)
    {
        return MedicalClinic::create($data);
    }

    /**
     * Обновляет данные медицинского учреждения
     */
    public function update(int $id, array $data): MedicalClinic
    {
        $medicalClinic = MedicalClinic::findOrFail($id);

        $medicalClinic->update($data);

        return $medicalClinic;
    }
}
