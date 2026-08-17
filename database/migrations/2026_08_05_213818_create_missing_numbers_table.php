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
        Schema::create('missing_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('king_planet_id')->constrained('planets')->cascadeOnDelete();
            $table->longText('missing_number_msg')->nullable();
            $table->longText('repetitive_number_donation')->nullable();
            $table->longText('repetitive_number_medicalIssues')->nullable();
            $table->longText('remedies')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missing_numbers');
    }
};
