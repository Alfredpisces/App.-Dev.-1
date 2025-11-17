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
        Schema::table('users', function (Blueprint $table) {
            // Add a string column for storing the user's timezone (e.g., 'Asia/Manila')
            $table->string('timezone')->default('UTC')->after('email')->comment('User preferred timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the timezone column if rolling back the migration
            $table->dropColumn('timezone');
        });
    }
};