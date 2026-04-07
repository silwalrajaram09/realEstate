<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->enum('listing_status', ['available', 'sold', 'rented'])->default('available')->after('is_featured');
            $table->text('rejection_reason')->nullable()->after('listing_status');
            $table->json('gallery')->nullable()->after('image');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_suspended');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['slug', 'is_featured', 'listing_status', 'rejection_reason', 'gallery']);
        });
    }
};
