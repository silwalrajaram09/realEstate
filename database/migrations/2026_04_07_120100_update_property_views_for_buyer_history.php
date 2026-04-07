<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            if (!Schema::hasColumn('property_views', 'buyer_id')) {
                $table->foreignId('buyer_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('property_views', 'viewed_at')) {
                $table->timestamp('viewed_at')->nullable()->after('property_id');
            }
        });

        DB::table('property_views')
            ->whereNull('buyer_id')
            ->update([
                'buyer_id' => DB::raw('user_id'),
                'viewed_at' => DB::raw('COALESCE(updated_at, created_at)'),
            ]);

        Schema::table('property_views', function (Blueprint $table) {
            $table->index(['buyer_id', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            $table->dropIndex(['buyer_id', 'viewed_at']);
            if (Schema::hasColumn('property_views', 'buyer_id')) {
                $table->dropConstrainedForeignId('buyer_id');
            }
            if (Schema::hasColumn('property_views', 'viewed_at')) {
                $table->dropColumn('viewed_at');
            }
        });
    }
};
