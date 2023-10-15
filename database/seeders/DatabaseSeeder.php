<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // generate permissions (clean, with policies, ignre prompts)
        Artisan::call('permissions:sync --clean --policies --yes-to-all');

        // generate roles
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $userRole = \Spatie\Permission\Models\Role::create(['name' => 'user']);

        // give all permissions to admin role
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // give all view permissions to user role
        $userRole->givePermissionTo(
            \Spatie\Permission\Models\Permission::query()
                ->where('name', 'like', 'view%')
                ->get()
        );

        // create admin user
        \App\Models\User::factory()
            ->create([
                'name' => 'Joe Bloggs',
                'email' => 'admin@demo.com',
            ])
            ->assignRole('admin');

        // create normal users
        \App\Models\User::factory(10)
            ->create()
            ->each(fn ($user) => $user->assignRole($userRole));

        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
