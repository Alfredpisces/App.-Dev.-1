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
        // Check if the column *does not* already exist
        if (!Schema::hasColumn('users', 'profile_picture')) {
            
            // If it doesn't exist, add it
Schema::table('users', function (Blueprint $table) {
    $table->string('profile_picture')->nullable()->after('updated_at'); // <-- FIX
});
        }
        // If the column *does* exist, this code block is skipped,
        // preventing the "Duplicate column" error.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the column *does* exist before trying to drop it
        if (Schema::hasColumn('users', 'profile_picture')) {
            
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('profile_picture');
            });
        }
    }
};