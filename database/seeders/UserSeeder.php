<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superUserRole = RoleUser::where('name_role', 'Super User')->first();
        $adminRole = RoleUser::where('name_role', 'Admin')->first();

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat di produksi!
            'role_user_id' => $superUserRole->id, // Kaitkan dengan peran Super User
            'email_verified_at' => now(),
        ]);

        // Buat user Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat di produksi!
            'role_user_id' => $adminRole->id, // Kaitkan dengan peran Admin
            'email_verified_at' => now(),
        ]);
    }
}
