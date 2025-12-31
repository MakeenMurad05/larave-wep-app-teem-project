<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إعادة ضبط الكاش الخاص بالصلاحيات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات
        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'edit tasks']);
        Permission::create(['name' => 'delete tasks']);

        // إنشاء أدوار (Roles) وتعيين الصلاحيات لها
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // تعيين الدور للمستخدم الأول (اختياري)
        $user = User::first();
        if ($user) {
            $user->assignRole($role);
        }
    }
}
