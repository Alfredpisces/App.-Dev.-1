<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Assuming you have a Role model

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'owner'], ['description' => 'Full administrative access to a business account.']);
        Role::firstOrCreate(['name' => 'accountant'], ['description' => 'Can view financials and manage entries.']);
        Role::firstOrCreate(['name' => 'team_member'], ['description' => 'Can only submit their own expenses.']);
    }
}