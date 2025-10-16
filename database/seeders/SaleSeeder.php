<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (optional—remove if you want to keep any)
        Sale::truncate();

        $today = Carbon::parse('2025-10-12'); // Current date: October 12, 2025
        $samples = [
            [
                'user_id' => 1, // Confirm this matches your user ID (see Step 2 if needed)
                'customer_name' => 'XYZ Ltd',
                'amount' => 4559.20,
                'sale_date' => '2025-09-25',
                'due_date' => null,
                'status' => 'draft',
                'is_taxable' => true,
                'notes' => 'Sample invoice 1',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'user_id' => 1,
                'customer_name' => 'XYZ Ltd',
                'amount' => 4003.55,
                'sale_date' => '2025-10-01',
                'due_date' => '2025-11-13',
                'status' => 'paid',
                'is_taxable' => false,
                'notes' => 'Sample invoice 2',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'user_id' => 1,
                'customer_name' => 'ABC Corp',
                'amount' => 4282.28,
                'sale_date' => '2025-09-20',
                'due_date' => '2025-10-11', // Past due date for testing overdue
                'status' => 'paid',
                'is_taxable' => false,
                'notes' => 'Sample invoice 3',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'user_id' => 1,
                'customer_name' => 'Tech Innovators',
                'amount' => 1609.84,
                'sale_date' => '2025-09-16',
                'due_date' => null,
                'status' => 'sent',
                'is_taxable' => false,
                'notes' => 'Sample invoice 4',
                'created_at' => $today,
                'updated_at' => $today,
            ],
            [
                'user_id' => 1,
                'customer_name' => 'Tech Innovators',
                'amount' => 6851.93,
                'sale_date' => '2025-10-01',
                'due_date' => null,
                'status' => 'sent',
                'is_taxable' => false,
                'notes' => 'Sample invoice 5',
                'created_at' => $today,
                'updated_at' => $today,
            ],
        ];

        foreach ($samples as $sample) {
            Sale::create($sample);
        }
    }
}