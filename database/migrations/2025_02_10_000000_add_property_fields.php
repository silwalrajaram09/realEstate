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
        // Add new property type values using string (SQLite doesn't support enum)
        Schema::table('properties', function (Blueprint $table) {
            // Change type column to accept more values
            $table->string('type')->change();
        });

        Schema::table('properties', function (Blueprint $table) {
            // Add new fields
            $table->string('image')->nullable()->after('area');
            $table->string('min_lease_months')->nullable()->after('price');
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();

            // Residential fields
            $table->integer('floor_no')->nullable()->after('bathrooms');
            $table->integer('total_floors')->nullable()->after('floor_no');
            $table->integer('year_built')->nullable()->after('total_floors');

            // Land fields
            $table->integer('road_access')->nullable()->after('year_built');
            $table->string('facing')->nullable()->after('road_access');
            $table->string('land_shape')->nullable()->after('facing');
            $table->string('plot_number')->nullable()->after('land_shape');

            // Commercial/Office fields
            $table->integer('parking_spaces')->nullable()->after('plot_number');

            // Industrial/Warehouse fields
            $table->decimal('clear_height', 8, 2)->nullable()->after('parking_spaces');
            $table->integer('loading_docks')->nullable()->after('clear_height');
            $table->integer('power_supply')->nullable()->after('loading_docks');

            // Features/Amenities
            $table->boolean('electricity')->default(false)->after('power_supply');
            $table->boolean('security')->default(false)->after('electricity');
            $table->boolean('garden')->default(false)->after('security');
            $table->boolean('balcony')->default(false)->after('garden');
            $table->boolean('gym')->default(false)->after('balcony');
            $table->boolean('lift')->default(false)->after('gym');
            $table->boolean('ac')->default(false)->after('lift');
            $table->boolean('fire_safety')->default(false)->after('ac');
            $table->boolean('internet')->default(false)->after('fire_safety');
            $table->boolean('loading_area')->default(false)->after('internet');

            // Availability
            $table->date('available_from')->nullable()->after('loading_area');

            // Ownership
            $table->string('ownership_type')->nullable()->after('available_from');
            $table->string('contact_number')->nullable()->after('ownership_type');

            // Add indexes for new searchable fields
            $table->index(['type']);
            $table->index(['category']);
            $table->index(['year_built']);
            $table->index(['available_from']);
            // Note: Boolean fields (parking, water, etc.) are NOT indexed 
            // as they have very low cardinality (0/1 values)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'image',
                'min_lease_months',
                'floor_no',
                'total_floors',
                'year_built',
                'road_access',
                'facing',
                'land_shape',
                'plot_number',
                'parking_spaces',
                'clear_height',
                'loading_docks',
                'power_supply',
                'electricity',
                'security',
                'garden',
                'balcony',
                'gym',
                'lift',
                'ac',
                'fire_safety',
                'internet',
                'loading_area',
                'available_from',
                'ownership_type',
                'contact_number',
            ]);

            // Reset type enum
            $table->string('type')->change();

            // Reset status
            $table->string('status')->default('approved')->change();
        });
    }
};

