<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserImageController extends Controller
{
    public function addImages(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'front' => 'required|max:2000|mimes:jpeg,png,webp',
            'back' => 'required|max:2000|mimes:jpeg,png,webp',
            'side' => 'required|max:2000|mimes:jpeg,png,webp',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $userStatus = Auth::user()->userInfoStatus()->orderBy('id', 'desc')->first();

            if ($userStatus->userImage()->first() === null) {
                //upload image front
                $file = $validation->valid()['front'];
                $destinationPath = 'assets/images/user/body/';
                $file_name1 = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name1);

                //upload image back
                $file = $validation->valid()['back'];
                $destinationPath = 'assets/images/user/body/';
                $file_name2 = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name2);

                //upload image side
                $file = $validation->valid()['side'];
                $destinationPath = 'assets/images/user/body/';
                $file_name3 = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name3);


                $data = array_merge(
                    $validation->valid(),
                    ["back" => $destinationPath . $file_name1],
                    ["front" => $destinationPath . $file_name2],
                    ["side" => $destinationPath . $file_name3]
                );
                $userStatus->userImage()->create($data);


                //add percantage
                $perecentage = (array)json_decode($userStatus->percentage);
                array_push($perecentage, 'image');
                $userNewStatus = $userStatus->update([
                    'percentage' => $perecentage
                ]);

                if (count($perecentage) == 4) {
                    $userStatus->order()->first()->update([
                        'status' => 'complete'
                    ]);
                }
            } else {
                //upload image front
                $file = $validation->valid()['front'];
                $destinationPath = 'assets/images/user/body/';
                $file_name1 = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name1);

                //upload image back
                $file = $validation->valid()['back'];
                $destinationPath = 'assets/images/user/body/';
                $file_name2 = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name2);

                //upload image side
                $file = $validation->valid()['side'];
                $destinationPath = 'assets/images/user/body/';
                $file_name3 = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name3);


                $data = array_merge(
                    $validation->valid(),
                    ["back" => $destinationPath . $file_name1],
                    ["front" => $destinationPath . $file_name2],
                    ["side" => $destinationPath . $file_name3]
                );

                $userStatus->userImage()->update($data);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['تصاویر شما با موفقیت ثبت شد.']]);
    }
}
