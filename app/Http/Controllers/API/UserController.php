<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    public function register(Request $request)
    {
        try {
            $customMessages = [
                'name.required' => 'Tên không được để trống',
                'email.required' => 'Email không được để trống',
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.max' => 'Mật khẩu tối đa chỉ 20 ký tự',
                'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ cái'
            ];

            if (!isset($request->otp)) {
                $otpExists = Redis::exists($request->email);
                if ($otpExists) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'OTP has been sent'
                    ], 500);
                } else {
                    $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'email' => 'required|email',
                        'password' => 'required|min:8|max:20|regex:/^(?=.*[a-zA-Z]).*$/',

                    ], $customMessages);


                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Validation failed',
                            'errors' => $errors
                        ], 422);
                    } else {
                        if (User::where('email', $request->email)->exists()) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Email đã tồn tại',
                            ], 422);
                        } else {
                            $otp = rand(100000, 999999);
                            Mail::to($request->email)->send(new OtpMail($otp));
                            Redis::set($request->email, $otp);
                            Redis::expire($request->email, 60);
                            return response()->json([
                                'status' => 'pending',
                                'message' => 'OPT đã được gửi tới email. Vui lòng xác minh email',
                            ], 201);
                        }
                    }
                }
            } else {
                $storedOtp = Redis::get($request->email);
                if ($storedOtp != $request->otp) {
                    return response()->json([
                        'status' => 'ErrorOTP',
                        'message' => 'Mã OTP không chính xác'
                    ]);
                } else {

                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = bcrypt($request->password);
                    $user->save();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Create customer successfully',
                    ], 201);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
        // Redis::del('dhan29112001@gmail.com');
    }
}
