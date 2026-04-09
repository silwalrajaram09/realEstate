<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->decimal('match_score', 5, 4)->nullable()->after('message')
                  ->comment('How well this property matches the buyer based on our algorithm');
            $table->json('match_details')->nullable()->after('match_score')
                  ->comment('Breakdown of the match components (price, location, etc)');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['match_score', 'match_details']);
        });
    }
};
