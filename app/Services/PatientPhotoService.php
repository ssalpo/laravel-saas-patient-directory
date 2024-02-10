<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PatientPhotoService
{
    public function store(int $id, array $photos): void
    {
        $patient = Patient::my('created_by')->findOrFail($id);

        foreach ($photos as $photo) {
            $patient->photos()->create([
                'url' => $photo?->store('photos', 'public'),
            ]);
        }
    }

    public function destroy(int $patientId, int $photoId): void
    {
        $patient = Patient::my('created_by')->findOrFail($patientId);

        DB::transaction(function () use ($patient, $photoId) {
            $photo = $patient->photos()
                ->whereId($photoId)
                ->firstOrFail();

            $photo->delete();

            Storage::disk('public')->delete($photo->url ?? '');
        });
    }

    public function updateLabel(int $patientId, int $photoId, string $label): void
    {
        Patient::my('created_by')
            ->findOrFail($patientId)
            ->photos()
            ->findOrFail($photoId)
            ->update(['label' => $label]);
    }
}
