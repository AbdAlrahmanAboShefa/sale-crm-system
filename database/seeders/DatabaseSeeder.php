<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Super Admin', 'Admin', 'Manager', 'Agent'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Super Admin (no tenant)
        User::firstOrCreate(
            ['email' => 'superadmin@crm.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'tenant_id' => null,
                'is_active' => true,
            ]
        )->assignRole('Super Admin');

        // Demo Tenant
        $demoTenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo'],
            [
                'name' => 'Demo Company',
                'plan' => 'pro',
                'is_active' => true,
                'trial_ends_at' => now()->addDays(30),
            ]
        );

        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@crm.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'tenant_id' => $demoTenant->id,
                'is_active' => true,
            ]
        );
        $admin->assignRole('Admin');

        // Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@crm.com'],
            [
                'name' => 'Manager User',
                'password' => bcrypt('password'),
                'tenant_id' => $demoTenant->id,
                'is_active' => true,
            ]
        );
        $manager->assignRole('Manager');

        // Agent Users
        $agent1 = User::firstOrCreate(
            ['email' => 'agent1@crm.com'],
            [
                'name' => 'Sales Agent 1',
                'password' => bcrypt('password'),
                'tenant_id' => $demoTenant->id,
                'is_active' => true,
            ]
        );
        $agent1->assignRole('Agent');

        $agent2 = User::firstOrCreate(
            ['email' => 'agent2@crm.com'],
            [
                'name' => 'Sales Agent 2',
                'password' => bcrypt('password'),
                'tenant_id' => $demoTenant->id,
                'is_active' => true,
            ]
        );
        $agent2->assignRole('Agent');

        // Sample Contacts for Demo Tenant
        $contacts = [
            ['name' => 'John Smith', 'email' => 'john@acme.com', 'company' => 'Acme Corp', 'phone' => '+1 555-0101'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah@techinc.com', 'company' => 'Tech Inc', 'phone' => '+1 555-0102'],
            ['name' => 'Mike Wilson', 'email' => 'mike@globalco.com', 'company' => 'Global Co', 'phone' => '+1 555-0103'],
            ['name' => 'Emily Brown', 'email' => 'emily@startup.io', 'company' => 'Startup.io', 'phone' => '+1 555-0104'],
            ['name' => 'David Lee', 'email' => 'david@enterprise.com', 'company' => 'Enterprise Ltd', 'phone' => '+1 555-0105'],
        ];

        foreach ($contacts as $contactData) {
            Contact::firstOrCreate(
                ['email' => $contactData['email']],
                [
                    'name' => $contactData['name'],
                    'email' => $contactData['email'],
                    'company' => $contactData['company'],
                    'phone' => $contactData['phone'],
                    'tenant_id' => $demoTenant->id,
                    'user_id' => $admin->id,
                    'status' => ['Lead', 'Prospect', 'Client'][array_rand(['Lead', 'Prospect', 'Client'])],
                    'source' => 'website',
                ]
            );
        }

        // Sample Deals for Demo Tenant
        $demoContacts = Contact::where('tenant_id', $demoTenant->id)->get();

        $deals = [
            ['title' => 'Acme Enterprise License', 'value' => 50000, 'stage' => 'Negotiation', 'probability' => 75],
            ['title' => 'Tech Inc Annual Plan', 'value' => 25000, 'stage' => 'Proposal', 'probability' => 50],
            ['title' => 'Global Co Starter Pack', 'value' => 12000, 'stage' => 'Qualified', 'probability' => 25],
            ['title' => 'Startup.io Monthly Subscription', 'value' => 5000, 'stage' => 'Contacted', 'probability' => 15],
            ['title' => 'Enterprise Ltd Expansion', 'value' => 75000, 'stage' => 'New', 'probability' => 10],
            ['title' => 'Acme Support Contract', 'value' => 15000, 'stage' => 'Won', 'probability' => 100],
            ['title' => 'Tech Inc Add-on', 'value' => 8000, 'stage' => 'Lost', 'probability' => 0],
        ];

        foreach ($deals as $index => $dealData) {
            Deal::firstOrCreate(
                ['title' => $dealData['title']],
                [
                    'title' => $dealData['title'],
                    'value' => $dealData['value'],
                    'stage' => $dealData['stage'],
                    'probability' => $dealData['probability'],
                    'tenant_id' => $demoTenant->id,
                    'user_id' => [$admin, $manager, $agent1, $agent2][$index % 4]->id,
                    'contact_id' => $demoContacts->random()->id,
                    'expected_close_date' => now()->addDays(rand(7, 60)),
                ]
            );
        }

        // Sample Activities
        $users = [$admin, $manager, $agent1, $agent2];
        $dealTitles = ['Acme Enterprise License', 'Tech Inc Annual Plan', 'Global Co Starter Pack'];

        $activities = [
            ['type' => 'Call', 'note' => 'Discussed pricing options and contract terms'],
            ['type' => 'Meeting', 'note' => 'Product demo presentation scheduled'],
            ['type' => 'Email', 'note' => 'Sent proposal document via email'],
            ['type' => 'Task', 'note' => 'Follow up with client next week'],
            ['type' => 'Demo', 'note' => 'Live product demo completed successfully'],
        ];

        foreach (range(1, 10) as $i) {
            $activity = $activities[array_rand($activities)];
            $deal = Deal::where('tenant_id', $demoTenant->id)->where('stage', '!=', 'Lost')->inRandomOrder()->first();
            $contact = Contact::where('tenant_id', $demoTenant->id)->inRandomOrder()->first();

            Activity::firstOrCreate(
                [
                    'tenant_id' => $demoTenant->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'deal_id' => $deal?->id,
                    'contact_id' => $contact?->id,
                    'type' => $activity['type'],
                ],
                [
                    'note' => $activity['note'].' #'.$i,
                    'is_done' => $i > 5,
                    'due_date' => now()->subDays(rand(-5, 10)),
                    'duration_minutes' => rand(15, 60),
                ]
            );
        }
    }
}
