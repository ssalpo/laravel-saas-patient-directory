<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Добавляет пользователя
     */
    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);

            $user->assignRole($data['role']);

            $user->medicalClinics()->sync($data['medical_clinic_ids']);

            return $user;
        });
    }

    /**
     * Обновляет данные пользователя
     */
    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        return DB::transaction(function () use ($data, $user) {
            $user->update($data);

            $user->syncRoles($data['role']);

            $user->medicalClinics()->sync($data['medical_clinic_ids']);

            return $user;
        });
    }

    /**
     * Переключает активность пользователя
     */
    public function toggleActivity(int $id): bool
    {
        $user = User::findOrFail($id);

        return $user->update(['is_active' => ! $user->is_active]);
    }
}
