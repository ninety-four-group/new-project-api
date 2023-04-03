<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Admin::factory(1)->create();
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'permissions' => json_encode(['*'])
        ]);

        Admin::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make('password'),
            "email_verified_at" => now(),
            "role_id" => $superAdminRole->id,
        ]);
        // \App\Models\Brand::factory(20)->create();
        // \App\Models\Category::factory(20)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
