<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin', 'Manager', 'Agent'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        User::firstOrCreate(
            ['email' => 'admin@crm.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
            ]
        )->assignRole('Admin');
    }
}
