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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('orderable_id');
            $table->string('orderable_type');
            $table->text('price');
            $table->text('turn_code')->nullable();
            $table->boolean('visit')->default(false);
            $table->enum('status', ['unpaid', 'paid_uncomplete', 'complete', 'received_program']);
            $table->string('tracking_serial')->nullable();
            $table->string('subscribe_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
