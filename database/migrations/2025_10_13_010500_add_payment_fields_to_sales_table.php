<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add a column to track the remaining balance on an invoice.
            // It is nullable to support older records created before this field existed.
            $table->decimal('outstanding_amount', 10, 2)->nullable()->after('amount');

            // Add a text column to store a running history of payments.
            // This is useful for tracking multiple partial payments.
            $table->text('payment_history')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // This allows you to undo the migration if needed.
            $table->dropColumn(['outstanding_amount', 'payment_history']);
        });
    }
};