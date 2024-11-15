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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->string('price');
            $table->string('off_price')->nullable();
            $table->boolean('visit');
            $table->string('visit_price')->nullable();
            $table->text('description');
            $table->integer('duration');
            $table->text('features')->nullable();
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
