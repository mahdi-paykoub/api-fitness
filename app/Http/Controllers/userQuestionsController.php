<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class userQuestionsController extends Controller
{
    public function addQuestions(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'us_hsitory' => 'required',
            'ideal_body' => 'required',
            'sport_history' => 'required',
            'training_place' => 'required',
            'physical_injury' => 'required',
            'physical_injury_text' => 'nullable',
            'heart_disease' => 'required',
            'heart_disease_text' => 'nullable',
            'gastro_sensitivity' => 'required',
            'gastro_sensitivity_text' => 'nullable',
            'body_heat' => 'required',
            'medicine' => 'required',
            'medicine_text' => 'nullable',
            'smoking' => 'required',
            'smoking_text' => 'nullable',
            'appetite' => 'required',
            'frequency_defecation' => 'required',
            'liver_enzymes' => 'required',
            'liver_enzymes_text' => 'nullable',
            'history_steroid' => 'required',
            'history_steroid_text' => 'nullable',
            'supplement_use' => 'required',
            'supplement_use_text' => 'nullable',
            'final_question' => 'nullable',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $userStatus = Auth::user()->userInfoStatus()->orderBy('id', 'desc')->first();
            if ($userStatus->userQuestion()->first() === null) {
                $userStatus->userQuestion()->create([
                    'us_hsitory' => $validation->valid()['us_hsitory'],
                    'ideal_body' => $validation->valid()['ideal_body'],
                    'sport_history' => $validation->valid()['sport_history'],
                    'training_place' => $validation->valid()['training_place'],
                    'physical_injury' => $validation->valid()['physical_injury'],
                    'physical_injury_text' => $validation->valid()['physical_injury_text'],
                    'heart_disease' => $validation->valid()['heart_disease'],
                    'heart_disease_text' => $validation->valid()['heart_disease_text'],
                    'gastro_sensitivity' => $validation->valid()['gastro_sensitivity'],
                    'gastro_sensitivity_text' => $validation->valid()['gastro_sensitivity_text'],
                    'body_heat' => $validation->valid()['body_heat'],
                    'medicine' => $validation->valid()['medicine'],
                    'medicine_text' => $validation->valid()['medicine_text'],
                    'smoking' => $validation->valid()['smoking'],
                    'smoking_text' => $validation->valid()['smoking_text'],
                    'appetite' => $validation->valid()['appetite'],
                    'frequency_defecation' => $validation->valid()['frequency_defecation'],
                    'liver_enzymes' => $validation->valid()['liver_enzymes'],
                    'liver_enzymes_text' => $validation->valid()['liver_enzymes_text'],
                    'history_steroid' => $validation->valid()['history_steroid'],
                    'history_steroid_text' => $validation->valid()['history_steroid_text'],
                    'supplement_use' => $validation->valid()['supplement_use'],
                    'supplement_use_text' => $validation->valid()['supplement_use_text'],
                    'final_question' => $validation->valid()['final_question'],
                ]);
                //add percantage
                $perecentage = (array)json_decode($userStatus->percentage);
                array_push($perecentage, 'question');
                $userNewStatus = $userStatus->update([
                    'percentage' => $perecentage
                ]);
                if (count($perecentage) == 4) {
                    $userStatus->order()->first()->update([
                        'status' => 'complete'
                    ]);
                }
            } else {
                $userStatus->userQuestion()->update([
                    'us_hsitory' => $validation->valid()['us_hsitory'],
                    'ideal_body' => $validation->valid()['ideal_body'],
                    'sport_history' => $validation->valid()['sport_history'],
                    'training_place' => $validation->valid()['training_place'],
                    'physical_injury' => $validation->valid()['physical_injury'],
                    'physical_injury_text' => $validation->valid()['physical_injury_text'],
                    'heart_disease' => $validation->valid()['heart_disease'],
                    'heart_disease_text' => $validation->valid()['heart_disease_text'],
                    'gastro_sensitivity' => $validation->valid()['gastro_sensitivity'],
                    'gastro_sensitivity_text' => $validation->valid()['gastro_sensitivity_text'],
                    'body_heat' => $validation->valid()['body_heat'],
                    'medicine' => $validation->valid()['medicine'],
                    'medicine_text' => $validation->valid()['medicine_text'],
                    'smoking' => $validation->valid()['smoking'],
                    'smoking_text' => $validation->valid()['smoking_text'],
                    'appetite' => $validation->valid()['appetite'],
                    'frequency_defecation' => $validation->valid()['frequency_defecation'],
                    'liver_enzymes' => $validation->valid()['liver_enzymes'],
                    'liver_enzymes_text' => $validation->valid()['liver_enzymes_text'],
                    'history_steroid' => $validation->valid()['history_steroid'],
                    'history_steroid_text' => $validation->valid()['history_steroid_text'],
                    'supplement_use' => $validation->valid()['supplement_use'],
                    'supplement_use_text' => $validation->valid()['supplement_use_text'],
                    'final_question' => $validation->valid()['final_question'],
                ]);
            }
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['سوالات با موفقیت ثبت شد.']]);
    }
}
