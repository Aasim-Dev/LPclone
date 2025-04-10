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
        Schema::create('wallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('order_type');
            $table->string('description');
            $table->string('payment_status');
            $table->string('payment_type');
            $table->string('credit_debit');
            $table->integer('amount');
            $table->integer('total');
            $table->integer('paypal_fee')->nullable();
            $table->integer('tax')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet');
    }
};
