<?php

namespace App\Http\Controllers\v1;

use App\Jobs\SendResetPasswordEmailJob;
use App\Jobs\SendTwoStepVerificationEmailJob;
use App\Jobs\SendVerificationEmailJob;
use App\User;
use App\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:api')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required'
        ]);
        ['username'=>$username,'password'=>$password] = $request->only(['username','password']);
        $user = User::query();
        if(!filter_var($username,FILTER_VALIDATE_EMAIL)){
            $user = $user->where('username',$username)->firstOrFail();
        }else{
            $user = $user->where('email',$username)->firstOrFail();
        }
        if (!Hash::check($password,$user->password) || is_null($user->getAttribute('email_verified_at')) || !is_null($user->setting->two_step_verification_code)){
            return response(['error'=>['message'=>'Invalid Username Or Password']],200);
        }
        if ($user->setting->two_step_verification_status){
            $code = Str::random(50);
            $user->setting()->update([
                "two_step_verification_code" => sha1($code)]
            );
            SendTwoStepVerificationEmailJob::dispatch($user,$code);
            return response(['message'=>"your Code has been sent to your Email . Please Confirm if this Account is yours"],200);
        }
        $token = $user->createToken('GrantClient')->accessToken;
        return response(compact('token'),200);
    }

    public function register(Request $request)
    {
        $credentials = $request->only(['username','email','password','password_confirmation']);
        $validation = Validator::make($credentials,[
           'username'=>'required|min:5|unique:users',
           'email'=>'required|min:6|email|unique:users',
           'password'=>['required','min:8'] ,
           'password_confirmation'=>'required|same:password' ,
        ]);
        if ($validation->fails()){
            return \response(['error'=>$validation->errors()],401);
        }
        $credentials['password'] = bcrypt($credentials['password']);
        unset($credentials['password_confirmation']);
        $user = User::create($credentials);
        $code = Str::random(50);
        $user->setting()->create([
            'email_verification_code' => sha1($code),
        ]);
        SendVerificationEmailJob::dispatch($user->load('setting'),$code);
        $message = "Your Account has been created . Please Confirm Your Email";
        return \response(['message'=>$message],201);
    }

    public function emailConfirm(Request $request,$code)
    {
        $user = UserSetting::where('email_verification_code',sha1($code))->first()->user->load('setting');
        if (is_null($user)){
            return \response(['Invalid code'],404);
        }
        $user->setAttribute('email_verified_at',now());
        $user->setting->update(['email_verification_code'=>null]);
        $user->save();
        return response(['Your Email Has Been Confirmed'],200);
    }

    public function resetPassword(Request $request)
    {
        $credentials = $request->only(['email']);
        $validation = Validator::make($credentials,[
            'email'=>'required|min:6|email',
        ]);
        if ($validation->fails()){
            return \response(['error'=>$validation->errors()],401);
        }
        $user = User::where('email',$credentials['email'])->first();
        if (is_null($user)){
            return \response(['error'=>"Invalid Credentials"],400);
        }
        $password = Str::random(16);
        $user->password = bcrypt($password);
        $user->save();
        SendResetPasswordEmailJob::dispatch($user,$password);
        return \response(['message'=>"your new Password has been sent to your Email"],200);
    }

    public function logout()
    {
        auth()->user()->token()->delete();
        return \response([],204);
    }

    public function twoStepVerification(Request $request,$code)
    {
        $userSetting = UserSetting::query()->where('two_step_verification_code',sha1($code))->firstOrFail();
        if ($userSetting->getAttribute('two_step_verification_code_expire_at') < now()){
            return response(['error'=>"Your Code has been expired"],400);
        }
        $userSetting->two_step_verification_code = null;
        $userSetting->two_step_verification_expire_at = null;
        $userSetting->save();
        return response(['message'=>'Now You Can Login'],200);
    }
}
