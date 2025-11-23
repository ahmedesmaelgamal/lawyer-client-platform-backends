<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Admin;
use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->insert([
            ['name' => 'admin', 'guard_name' => 'admin'],
        ]);

    }

}
