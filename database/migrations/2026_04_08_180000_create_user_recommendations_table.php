<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        // Set the migration filename to be current for the user
    }

    public function up(): void
    {
        Schema::create('user_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null for global/guest recommendations
            $table->unsignedBigInteger('property_id');
            $table->decimal('score', 8, 4);
            $table->json('reasons')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'score']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_recommendations');
    }
};
