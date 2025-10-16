<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vendor')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('category');
            $table->string('status')->default('paid');
            $table->string('receipt_path')->nullable();
            $table->boolean('is_tax_deductible')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};