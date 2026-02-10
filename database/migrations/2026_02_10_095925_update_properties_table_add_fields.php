<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Change type to string if needed (Doctrine-friendly)
            $table->string('type')->change();
        });

        Schema::table('properties', function (Blueprint $table) {
            // Add new fields only if they don't exist
            $fields = [
                'image' => ['type' => 'string', 'nullable' => true, 'after' => 'area'],
                'min_lease_months' => ['type' => 'string', 'nullable' => true, 'after' => 'price'],
                'floor_no' => ['type' => 'integer', 'nullable' => true, 'after' => 'bathrooms'],
                'total_floors' => ['type' => 'integer', 'nullable' => true, 'after' => 'floor_no'],
                'year_built' => ['type' => 'integer', 'nullable' => true, 'after' => 'total_floors'],
                'road_access' => ['type' => 'integer', 'nullable' => true, 'after' => 'year_built'],
                'facing' => ['type' => 'string', 'nullable' => true, 'after' => 'road_access'],
                'land_shape' => ['type' => 'string', 'nullable' => true, 'after' => 'facing'],
                'plot_number' => ['type' => 'string', 'nullable' => true, 'after' => 'land_shape'],
                'parking_spaces' => ['type' => 'integer', 'nullable' => true, 'after' => 'plot_number'],
                'clear_height' => ['type' => 'decimal', 'precision' => 8, 'scale' => 2, 'nullable' => true, 'after' => 'parking_spaces'],
                'loading_docks' => ['type' => 'integer', 'nullable' => true, 'after' => 'clear_height'],
                'power_supply' => ['type' => 'integer', 'nullable' => true, 'after' => 'loading_docks'],
                'electricity' => ['type' => 'boolean', 'default' => false, 'after' => 'power_supply'],
                'security' => ['type' => 'boolean', 'default' => false, 'after' => 'electricity'],
                'garden' => ['type' => 'boolean', 'default' => false, 'after' => 'security'],
                'balcony' => ['type' => 'boolean', 'default' => false, 'after' => 'garden'],
                'gym' => ['type' => 'boolean', 'default' => false, 'after' => 'balcony'],
                'lift' => ['type' => 'boolean', 'default' => false, 'after' => 'gym'],
                'ac' => ['type' => 'boolean', 'default' => false, 'after' => 'lift'],
                'fire_safety' => ['type' => 'boolean', 'default' => false, 'after' => 'ac'],
                'internet' => ['type' => 'boolean', 'default' => false, 'after' => 'fire_safety'],
                'loading_area' => ['type' => 'boolean', 'default' => false, 'after' => 'internet'],
                'available_from' => ['type' => 'date', 'nullable' => true, 'after' => 'loading_area'],
                'ownership_type' => ['type' => 'string', 'nullable' => true, 'after' => 'available_from'],
                'contact_number' => ['type' => 'string', 'nullable' => true, 'after' => 'ownership_type'],
            ];

            foreach ($fields as $name => $options) {
                if (!Schema::hasColumn('properties', $name)) {
                    if ($options['type'] === 'string') {
                        $table->string($name)->nullable($options['nullable'] ?? false)->after($options['after'] ?? null);
                    } elseif ($options['type'] === 'integer') {
                        $table->integer($name)->nullable($options['nullable'] ?? false)->after($options['after'] ?? null);
                    } elseif ($options['type'] === 'decimal') {
                        $table->decimal($name, $options['precision'], $options['scale'])->nullable($options['nullable'] ?? false)->after($options['after'] ?? null);
                    } elseif ($options['type'] === 'boolean') {
                        $table->boolean($name)->default($options['default'] ?? false)->after($options['after'] ?? null);
                    } elseif ($options['type'] === 'date') {
                        $table->date($name)->nullable($options['nullable'] ?? false)->after($options['after'] ?? null);
                    }
                }
            }

            // Add indexes if they don't exist
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('properties');

            if (!array_key_exists('properties_type_index', $indexes)) {
                $table->index(['type']);
            }
            if (!array_key_exists('properties_category_index', $indexes)) {
                $table->index(['category']);
            }
            if (!array_key_exists('properties_year_built_index', $indexes)) {
                $table->index(['year_built']);
            }
            if (!array_key_exists('properties_available_from_index', $indexes)) {
                $table->index(['available_from']);
            }
        });

        // Fix enum column without Doctrine errors
        // Step 1: Update invalid status values to 'pending'
        DB::table('properties')->whereNotIn('status', ['pending', 'approved', 'rejected'])
            ->update(['status' => 'pending']);

        // Step 2: Modify status enum safely
        DB::statement("ALTER TABLE properties MODIFY status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = [
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
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('properties', $column)) {
                    $table->dropColumn($column);
                }
            }

            $table->string('type')->change();

            // Leave status as is
        });
    }
};
