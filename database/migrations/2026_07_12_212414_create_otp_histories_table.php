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
        Schema::create('otp_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('email');
            $table->string('otp', 255); // Hashed OTP
            $table->string('type')->default('login'); // login, register, forgot_password

            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();

            $table->unsignedTinyInteger('attempts')->default(0);

            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_histories');
    }
};
