<?php

namespace Database\Seeders;

use App\Enums\ModuleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $guardName = getCurrentGuardName() ?? Auth::getDefaultDriver();

        foreach (ModuleEnum::cases() as $case) {
            foreach ($case->permissions() as $permission) {
                Permission::query()
                    ->updateOrCreate([
                        'name' => $permission,
                    ], [
                        'name' => $permission,
                        'guard_name' => 'admin',
                    ]);
            }
        }

        $role = Role::query()->find(1);

        $permissions = Permission::all();

        $role->syncPermissions($permissions);

    }


}
