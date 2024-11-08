<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userQuestions extends Model
{
    use HasFactory;

    protected $fillable = [
        'us_hsitory',
        'ideal_body',
        'sport_history',
        'training_place',
        'physical_injury',
        'physical_injury_text',
        'heart_disease',
        'heart_disease_text',
        'gastro_sensitivity',
        'gastro_sensitivity_text',
        'body_heat',
        'medicine',
        'medicine_text',
        'smoking',
        'smoking_text',
        'appetite',
        'frequency_defecation',
        'liver_enzymes',
        'liver_enzymes_text',
        'history_steroid',
        'history_steroid_text',
        'supplement_use',
        'supplement_use_text',
        'final_question',
        'user_info_status_id',
    ];
}
