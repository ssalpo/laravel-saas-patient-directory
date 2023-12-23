<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Добавляет новую роль
     */
    public function store(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create($data + ['guard_name' => 'web']);

            $role->syncPermissions($data['permissions']);

            return $role;
        });
    }

    /**
     * Обновляет данные роли
     */
    public function update(int $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data) {
            $role = Role::findOrFail($id);

            $role->update($data);

            $role->syncPermissions($data['permissions']);

            return $role;
        });
    }
}
