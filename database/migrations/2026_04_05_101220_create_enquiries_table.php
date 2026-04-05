<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();

            // Who sent the enquiry
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('message')->nullable();

            // Which property
            $table->foreignId('property_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Logged-in buyer (optional — guest can also enquire)
            $table->foreignId('buyer_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // The seller who owns the property
            $table->foreignId('seller_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Status: new | read | replied | closed
            $table->enum('status', ['new', 'read', 'replied', 'closed'])
                  ->default('new');

            // Seller's reply
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};