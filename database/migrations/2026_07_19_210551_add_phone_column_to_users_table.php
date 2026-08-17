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
        Schema::table('users', function (Blueprint $table) {

            $table->string('user_id')->after('id');
            $table->integer('phone_code')
                ->nullable()
                ->constrained('countries', 'id')
                ->cascadeOnDelete()->after('email_verified_at');

            $table->string('phone', 20)->nullable()->after('phone_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
