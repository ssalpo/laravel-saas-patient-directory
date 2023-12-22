<?php

namespace App\Services;

use App\Exceptions\DoctorPatientsNotMovedException;
use App\Models\Doctor;

class DoctorService
{
    public function store(array $data): Doctor
    {
        return Doctor::create($data);
    }

    public function update(int $id, array $data): Doctor
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->update($data);

        return $doctor;
    }

    /**
     * Удаляет доктора
     *
     * @throws DoctorPatientsNotMovedException
     */
    public function destroy(int $id): bool
    {
        $doctor = Doctor::withCount('patients')->findOrFail($id);

        if ($doctor->patients_count) {
            throw new DoctorPatientsNotMovedException;
        }

        return $doctor->delete();
    }
}
