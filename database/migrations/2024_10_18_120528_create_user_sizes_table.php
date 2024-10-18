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
        Schema::create('user_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('height');
            $table->string('weight');
            $table->string('neck')->nullable();
            $table->string('shoulder')->nullable();
            $table->string('arm')->nullable();
            $table->string('contracted_arm')->nullable();
            $table->string('forearm')->nullable();
            $table->string('wrist')->nullable();
            $table->string('chest')->nullable();
            $table->string('belly')->nullable();
            $table->string('waist')->nullable();
            $table->string('hips')->nullable();
            $table->string('thigh')->nullable();
            $table->string('leg')->nullable();
            $table->string('ankle')->nullable();

            $table->unsignedBigInteger('user_info_status_id');
            $table->foreign('user_info_status_id')->references('id')->on('user_info_statuses')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sizes');
    }
};
