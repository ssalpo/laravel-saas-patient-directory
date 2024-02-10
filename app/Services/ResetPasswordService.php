<?php

namespace App\Services;

use App\Models\User;

class ResetPasswordService
{
    public function firstly(int $userId, string $password): bool
    {
        return User::findOrFail($userId)->update([
            'is_password_reset' => true,
            'password' => $password,
        ]);
    }
}
