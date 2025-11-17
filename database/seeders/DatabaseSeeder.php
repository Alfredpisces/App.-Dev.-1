<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Budget;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Assume user ID 1 exists; create if needed
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'business_name' => 'Test Business',
                'password' => Hash::make('password'),
            ]
        );

        $userId = $user->id;

        // Sample Dates (Back to 2024 for trends)
        $now = Carbon::now();  // 2025-10-02
        $lastYear = $now->copy()->subYear();

        // Seed Incomes (Varied sources, statuses)
        Income::factory(15)->create([
            'user_id' => $userId,
            'sale_date' => $this->randomDate($lastYear, $now),
            'source' => fn() => collect(['sales', 'services', 'other'])->random(),
            'payment_method' => fn() => collect(['cash', 'card', 'bank'])->random(),
            'currency' => 'PHP',
            'status' => 'completed',
        ])->each(function ($income) {
            $income->category = $income->source;  // Map for consistency
            $income->save();
        });

        // Seed Expenses (Varied categories)
        Expense::factory(12)->create([
            'user_id' => $userId,
            'expense_date' => $this->randomDate($lastYear, $now),
            'category' => fn() => collect(['raw_materials', 'transport', 'utilities', 'salaries', 'other'])->random(),
            'currency' => 'PHP',
        ]);

        // Seed Budgets (For variances)
        Budget::factory(5)->create([
            'user_id' => $userId,
            'start_date' => fn() => Carbon::now()->subMonth()->startOfMonth(),
            'end_date' => fn() => Carbon::now()->endOfMonth(),
            'budget_period' => fn() => collect(['month', 'year'])->random(),
            'category' => fn() => collect(['sales', 'services', 'other'])->random(),
        ]);

        echo "Test data seeded for User ID: $userId\n";
    }

    protected function randomDate(Carbon $start, Carbon $end): Carbon
    {
        $days = $start->diffInDays($end);
        return $start->copy()->addDays(rand(0, $days));
    }
}