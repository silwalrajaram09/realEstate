<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            // Buy or Sell
            $table->string('purpose')->default('buy');

            // Flat, House, Land, Commercial, Office, Warehouse
            $table->string('type')->default('flat');

            // Residential / Commercial / Industrial
            $table->string('category')->default('residential');

            $table->decimal('price', 12, 2);
            $table->string('location');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area'); // in sq ft

            // Extra features (for algorithm)
            $table->boolean('parking')->default(false);
            $table->boolean('water')->default(true);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });

        // Add indexes for better query performance with large datasets
        Schema::table('properties', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['purpose', 'type']);
            $table->index(['price']);
            $table->index(['location']);

            // Full-text index for title and description search
            $table->text('title')->fullText()->change();
            $table->text('description')->nullable()->fullText()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
