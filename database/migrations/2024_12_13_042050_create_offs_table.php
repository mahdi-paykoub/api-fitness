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
        Schema::create('offs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('usage')->default(0);
            $table->integer('max_usage');
            $table->enum('type',  ['percent', 'amount']);
            $table->string('value');
            $table->boolean('all_user');
            $table->enum('for',  ['plan', 'course', 'all']);
            $table->timestamps();
        });
        Schema::create('off_user', function (Blueprint $table) {
            $table->unsignedBigInteger('off_id');
            $table->foreign('off_id')->references('id')->on('offs')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['off_id', 'user_id']);
        });
        Schema::create('offables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('off_id')->constrained()->onDelete('cascade');
            $table->morphs('offable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offs');
        Schema::dropIfExists('offables');
        Schema::dropIfExists('off_user');
    }
};
