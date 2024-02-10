<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:sync-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permissions = [
            ['name' => 'manage_users', 'readable_name' => 'Управлять пользователями'],
            ['name' => 'manage_roles', 'readable_name' => 'Управлять ролями'],
        ];

        foreach ($permissions as $permission) {
            if (! Permission::whereName($permission['name'])->exists()) {
                Permission::create($permission);

                $this->info(sprintf('Created %s permission', $permission['name']));
            }
        }

        if ($role = Role::where('name', 'admin')->first()) {
            $role->syncPermissions(array_column($permissions, 'name'));
        }

        return Command::SUCCESS;
    }
}
