<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // generate permissions (clean, with policies, ignre prompts)
        Artisan::call('permissions:sync --clean --policies --yes-to-all');

        // generate roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // give all permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // give all view permissions to user role
        $userRole->givePermissionTo(
            Permission::query()
                ->where('name', 'like', 'view%')
                ->get()
        );

        // create admin user
        User::factory()
            ->create([
                'name' => 'Joe Bloggs',
                'email' => 'admin@demo.com',
            ])
            ->assignRole($adminRole);

        // create normal test user
        User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'user@demo.com',
            ])
            ->assignRole($userRole);

        // create normal users
        User::factory(10)
            ->create()
            ->each(fn ($user) => $user->assignRole($userRole));
    }
}
