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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('website_id');
            $table->string('host_url');
            $table->integer('da');
            $table->string('tat');
            $table->integer('semrush');
            $table->decimal('guest_post_price', 10, 2)->nullable();
            $table->decimal('linkinsertion_price', 10, 2)->nullable();
            $table->enum('status', ['wishilist', 'cart'])->default('cart');
            $table->enum('type', ['provide_content', 'guest_post', 'link_insertion'])->nullable();
            $table->string('language')->nullable();
            $table->string('attachment')->nullable();
            $table->string('special_instruction')->nullable();
            $table->string('existing_post_url')->nullable();
            $table->string('target_url')->nullable();
            $table->string('anchor_text')->nullable();
            $table->string('special_note')->nullable();
            $table->unsignedBigInteger('response_cart_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
