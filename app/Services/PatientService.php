<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Arr;

class PatientService
{
    /**
     * Добавляет нового пациента
     */
    public function store(array $data)
    {
        return Patient::create($data);
    }

    /**
     * Обновляет данные пациента
     */
    public function update(int $id, array $data)
    {
        $patient = Patient::my('created_by')->findOrFail($id);

        $patient->update($data);

        return $patient;
    }

    /**
     * Сохраняет ответ итогового результата диагноза
     */
    public function saveReport(int $id, array $data)
    {
        $fieldsToSave = ['note', 'morbi', 'comment', 'vitae', 'lab_workup', 'diagnosis', 'mkb', 'treatment', 'stain'];

        return tap(
            Patient::my('created_by')->findOrFail($id),
            fn ($p) => $p->update(Arr::only($data, $fieldsToSave))
        );
    }

    /**
     * Делится пациентом с другим врачом для получения консультации
     */
    public function share(array $data): Patient
    {
        $patient = Patient::findOrFail($data['patient_id']);

        $patient->update([
            'share_to_user_id' => $data['user_id'],
            'is_share_notification_viewed' => false,
        ]);

        return $patient;
    }

    /**
     * Устанавливает статус для поделенных пациентов
     */
    public function markSharedAsViewed(array $patientIds)
    {
        return Patient::whereIn('id', $patientIds)->update([
            'is_share_notification_viewed' => true,
        ]);
    }
}
