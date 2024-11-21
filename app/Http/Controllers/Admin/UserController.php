<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Program;
use App\Models\User;
use App\Models\UserInfoStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت کاربران بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $users]);
    }

    public function getTicketableUsers()
    {
        try {
            $users = User::where('status', '!=', null)->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت کاربران بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $users]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $user = User::create($validation->valid());
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['کاربر با موفقیت افزوده شد.']]);
    }

    public function storUserPrevInfos(Request $request)
    {
        $validation = Validator::make($request->all(), [
            //plan
            'plan_id' => 'required',
            'visit' => 'required',

            //pragram
            'program_title' => 'required',
            'program_file' => 'required',

            //image
            'front' => 'required',
            'back' => 'required',
            'side' => 'required',


            //size
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

            //questions
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
            $user = User::where('phone', $validation->valid()['phone'])->first();

            $plan = Plan::where('id', $validation->valid()['plan_id'])->first();
            if ($user != null) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'orderable_id' => $validation->valid()['plan_id'],
                    'orderable_type' => 'App\Models\Plan',
                    'price' => $plan->price,
                    'visit' => $validation->valid()['visit'] == 1 ? 1 : 0,
                    'status' => 'received_program',

                ]);
                $userStatus = UserInfoStatus::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'percentage' => json_encode(['question', 'image', 'size']),
                ]);

                $program_file = $validation->valid()['program_file'];
                $destinationPath = 'assets/files/programs/';
                $file_name = rand(1, 9999) . '-' . $program_file->getClientOriginalName();
                $program_file->move(public_path($destinationPath), $file_name);
                $file =  $destinationPath . $file_name;

                $program = Program::create([
                    'title' => $validation->valid()['program_title'],
                    'file' => $file,
                    'user_info_status_id' => $userStatus->id,
                ]);

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
                $user->update([
                    'status' => json_encode(['plan'])
                ]);
            } else {
                return response()->json(['status' => false, 'message' => ['کاربری با این شماره تلفن یافت نشد.']]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['اطلاعات کاربر با موفقیت ثبت شد.']]);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در حذف بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['کاربر با موفقیت حذف شد.']]);

    }
}
