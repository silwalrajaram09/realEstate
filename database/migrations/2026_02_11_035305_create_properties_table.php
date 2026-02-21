<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('purpose')->default('buy');
            $table->string('type')->default('flat');
            $table->string('category')->default('residential');

            $table->decimal('price', 12, 2);
            $table->string('location');

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area');

            $table->boolean('parking')->default(false);
            $table->boolean('water')->default(true);

            $table->string('image')->nullable();
            $table->string('min_lease_months')->nullable();

            $table->integer('floor_no')->nullable();
            $table->integer('total_floors')->nullable();
            $table->integer('year_built')->nullable();
            $table->integer('road_access')->nullable();

            $table->string('facing')->nullable();
            $table->string('land_shape')->nullable();
            $table->string('plot_number')->nullable();

            $table->integer('parking_spaces')->nullable();
            $table->decimal('clear_height', 8, 2)->nullable();
            $table->integer('loading_docks')->nullable();
            $table->integer('power_supply')->nullable();

            $table->boolean('electricity')->default(false);
            $table->boolean('security')->default(false);
            $table->boolean('garden')->default(false);
            $table->boolean('balcony')->default(false);
            $table->boolean('gym')->default(false);
            $table->boolean('lift')->default(false);
            $table->boolean('ac')->default(false);
            $table->boolean('fire_safety')->default(false);
            $table->boolean('internet')->default(false);
            $table->boolean('loading_area')->default(false);

            $table->date('available_from')->nullable();
            $table->string('ownership_type')->nullable();
            $table->string('contact_number')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();

            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['purpose', 'type']);
            $table->index('price');
            $table->index('location');
            $table->index(['latitude', 'longitude']);
            $table->index('category');
            $table->index('year_built');
            $table->index('available_from');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
