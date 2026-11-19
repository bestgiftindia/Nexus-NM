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
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();

            $table->string('txnid', 25)->unique();

            $table->string('name');
            $table->string('email');
            $table->string('mobile', 10);
            $table->date('dob');
            $table->string('gender');

            $table->decimal('amount', 10, 2);

            $table->string('status')->default('pending');

            $table->string('mihpayid')->nullable();
            $table->string('bank_ref_num')->nullable();

            $table->json('payu_response')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
