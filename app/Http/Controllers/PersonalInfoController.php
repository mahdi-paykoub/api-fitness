<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PersonalInfoController extends Controller
{
    public function storePersonalInfo(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required',
            'day' => 'required',
            'state' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'how_know' => 'required',
            'how_know_text' => 'nullable',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        $birth_date = $validation->valid()['year'] . '-' . $validation->valid()['month'] . '-' . $validation->valid()['day'];
        try {
            $personal = Auth::user()->personalInfo()->create([
                'birth_date' => $birth_date,
                'state' => $validation->valid()['state'],
                'city' => $validation->valid()['city'],
                'gender' => $validation->valid()['gender'],
                'how_know' => $validation->valid()['how_know'],
                'how_know_text' => $validation->valid()['how_know_text'],
            ]);
            //add percantage
            $userStatus = $personal->user()->first()->userInfoStatus()->orderBy('id', 'desc')->first();
            $perecentage = (array)json_decode($userStatus->percentage);
            array_push($perecentage, 'personal');
            $userNewStatus = $userStatus->update([
                'percentage' => $perecentage
            ]);
            
            if (count($perecentage) == 4) {
                $userStatus->order()->first()->update([
                    'status' => 'complete'
                ]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['اطلاعات شما با موفقیت ثبت شد.']]);
    }

    public function getUserPersonalInfo()
    {
        try {
            $personalInfo = Auth::user()->personalInfo()->first();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $personalInfo]);
    }
}
