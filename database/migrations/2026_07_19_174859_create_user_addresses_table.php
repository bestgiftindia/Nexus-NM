<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();

            // User delete hone par address delete
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('address')->nullable();

            // Country delete hone par country_id NULL
            $table->integer('country_id')
                ->nullable()
                ->constrained('countries')
                ->cascadeOnDelete();

            // State delete hone par state_id NULL
            $table->integer('state_id')
                ->nullable()
                ->constrained('states')
                ->cascadeOnDelete();

            // City delete hone par city_id NULL
            $table->integer('city_id')
                ->nullable()
                ->constrained('cities')
                ->cascadeOnDelete();

            $table->string('zipcode', 20)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
