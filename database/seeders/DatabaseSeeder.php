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
                'name' => 'Admin User',
                'password' => 'password',
            ]
        )->assignRole('Admin');

        User::firstOrCreate(
            ['email' => 'manager@crm.com'],
            [
                'name' => 'Manager User',
                'password' => 'password',
            ]
        )->assignRole('Manager');

        User::firstOrCreate(
            ['email' => 'agent1@crm.com'],
            [
                'name' => 'Sales Agent 1',
                'password' => 'password',
            ]
        )->assignRole('Agent');

        User::firstOrCreate(
            ['email' => 'agent2@crm.com'],
            [
                'name' => 'Sales Agent 2',
                'password' => 'password',
            ]
        )->assignRole('Agent');
    }
}
