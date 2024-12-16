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
        Schema::create('subscribe_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('type',  ['percent', 'amount']);
            $table->string('value');
            $table->enum('for',  ['plan', 'course', 'all']);
            $table->integer('score')->default(0);
            $table->boolean('active')->default(1);
            $table->integer('usage')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribe_codes');
    }
};
