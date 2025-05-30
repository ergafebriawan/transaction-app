<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleUser;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleUser::create([
            'name_role' => 'Super User',
            'previleges' => null
        ]);

        RoleUser::create([
            'name_role' => 'Admin',
            'previleges' => null
        ]);
    }
}
