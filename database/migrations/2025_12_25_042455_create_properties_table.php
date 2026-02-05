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
            $table->enum('purpose', ['buy', 'sell']);

            // Flat, House, Land
            $table->enum('type', ['flat', 'house', 'land']);

            // Residential / Commercial
            $table->enum('category', ['residential', 'commercial']);

            $table->decimal('price', 12, 2);
            $table->string('location');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area'); // in sq ft

            // Extra features (for algorithm)
            $table->boolean('parking')->default(false);
            $table->boolean('water')->default(true);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('status')->default('approved');

            $table->timestamps();
        });

        // Add indexes for better query performance with large datasets
        Schema::table('properties', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['purpose', 'type']);
            $table->index(['price']);
            $table->index(['location']);
            $table->fullText(['title', 'location']);
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
