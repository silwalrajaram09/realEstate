<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change status column to ENUM with default 'pending'
        DB::statement("
            ALTER TABLE properties
            MODIFY status ENUM('pending', 'approved', 'rejected')
            NOT NULL
            DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to string if needed
        DB::statement("
            ALTER TABLE properties
            MODIFY status VARCHAR(255)
            NOT NULL
            DEFAULT 'approved'
        ");
    }
};
