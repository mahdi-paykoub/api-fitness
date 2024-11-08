<?php

namespace App\Http\Controllers;

use App\Models\UserSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserSizeController extends Controller
{
    public function addSizes(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'height' => 'required',
            'weight' => 'required',
            'neck' => 'nullable',
            'shoulder' => 'nullable',
            'arm' => 'nullable',
            'contracted_arm' => 'nullable',
            'forearm' => 'nullable',
            'wrist' => 'nullable',
            'chest' => 'nullable',
            'belly' => 'nullable',
            'waist' => 'nullable',
            'hips' => 'nullable',
            'thigh' => 'nullable',
            'leg' => 'nullable',
            'ankle' => 'nullable',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);



        try {
            $userStatus = Auth::user()->userInfoStatus()->orderBy('id', 'desc')->first();
            if ($userStatus->userSize()->first() === null) {
                $userStatus->userSize()->create([
                    'height' => $validation->valid()['height'],
                    'weight' => $validation->valid()['weight'],
                    'neck' => $validation->valid()['neck'],
                    'shoulder' => $validation->valid()['shoulder'],
                    'arm' => $validation->valid()['arm'],
                    'contracted_arm' => $validation->valid()['contracted_arm'],
                    'forearm' => $validation->valid()['forearm'],
                    'wrist' => $validation->valid()['wrist'],
                    'chest' => $validation->valid()['chest'],
                    'belly' => $validation->valid()['belly'],
                    'waist' => $validation->valid()['waist'],
                    'hips' => $validation->valid()['hips'],
                    'thigh' => $validation->valid()['thigh'],
                    'leg' => $validation->valid()['leg'],
                    'ankle' => $validation->valid()['ankle'],
                    'neck' => $validation->valid()['neck'],
                ]);
                //add percantage
                $perecentage = (array)json_decode($userStatus->percentage);
                array_push($perecentage, 'size');

                $userNewStatus = $userStatus->update([
                    'percentage' => $perecentage
                ]);

                if (count($perecentage) == 4) {
                    $userStatus->order()->first()->update([
                        'status' => 'complete'
                    ]);
                }
            } else {
                $userStatus->userSize()->update([
                    'height' => $validation->valid()['height'],
                    'weight' => $validation->valid()['weight'],
                    'neck' => $validation->valid()['neck'],
                    'shoulder' => $validation->valid()['shoulder'],
                    'arm' => $validation->valid()['arm'],
                    'contracted_arm' => $validation->valid()['contracted_arm'],
                    'forearm' => $validation->valid()['forearm'],
                    'wrist' => $validation->valid()['wrist'],
                    'chest' => $validation->valid()['chest'],
                    'belly' => $validation->valid()['belly'],
                    'waist' => $validation->valid()['waist'],
                    'hips' => $validation->valid()['hips'],
                    'thigh' => $validation->valid()['thigh'],
                    'leg' => $validation->valid()['leg'],
                    'ankle' => $validation->valid()['ankle'],
                    'neck' => $validation->valid()['neck'],
                ]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            // return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['اطلاعات شما با موفقیت ثبت شد.']]);
    }

    public function getUserDefaultInfoes()
    {

        try {
            $userStatus = Auth::user()->userInfoStatus()->orderBy('id', 'desc')->first();
            $sizes = $userStatus->userSize()->first();
            $question = $userStatus->userQuestion()->first();
            $image = $userStatus->userImage()->first();
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json([
            'status' => true,
            'size' => $sizes,
            'question' => $question,
            'image' => $image,
        ]);
    }
}
