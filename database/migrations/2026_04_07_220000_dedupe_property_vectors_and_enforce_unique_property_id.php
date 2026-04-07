<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('property_vectors')) {
            return;
        }

        // Keep newest row per property_id, remove older duplicates.
        DB::statement(
            'DELETE pv1 FROM property_vectors pv1
             INNER JOIN property_vectors pv2
               ON pv1.property_id = pv2.property_id
              AND pv1.id < pv2.id'
        );

        $indexExists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'property_vectors')
            ->where('index_name', 'property_vectors_property_id_unique')
            ->exists();

        if (!$indexExists) {
            DB::statement(
                'ALTER TABLE property_vectors
                 ADD UNIQUE INDEX property_vectors_property_id_unique (property_id)'
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('property_vectors')) {
            return;
        }

        $indexExists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'property_vectors')
            ->where('index_name', 'property_vectors_property_id_unique')
            ->exists();

        if ($indexExists) {
            DB::statement(
                'ALTER TABLE property_vectors
                 DROP INDEX property_vectors_property_id_unique'
            );
        }
    }
};
