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
        Schema::create('user_questions', function (Blueprint $table) {
            $table->id();

            $table->boolean('us_hsitory');
            $table->text('ideal_body');
            $table->text('sport_history');
            $table->text('training_place');
            $table->boolean('physical_injury');
            $table->text('physical_injury_text')->nullable();
            $table->boolean('heart_disease');
            $table->text('heart_disease_text');
            $table->boolean('gastro_sensitivity');
            $table->text('gastro_sensitivity_text');
            $table->text('body_heat');
            $table->boolean('medicine');
            $table->text('medicine_text');
            $table->boolean('smoking');
            $table->text('smoking_text');
            $table->text('appetite');
            $table->text('frequency_defecation');
            $table->boolean('liver_enzymes');
            $table->text('liver_enzymes_text');
            $table->boolean('history_steroid');
            $table->text('history_steroid_text');
            $table->boolean('supplement_use');
            $table->text('supplement_use_text');
            $table->text('final_question');

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
        Schema::dropIfExists('user_questions');
    }
};
