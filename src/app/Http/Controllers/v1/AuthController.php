<?php

namespace App\Http\Controllers\v1;

use App\Jobs\SendVerificationEmailJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:api');
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
        if (!Hash::check($password,$user->password) || is_null($user->getAttribute('email_verified_at'))){
            return response(['error'=>['message'=>'Invalid Username Or Password']],200);
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
        $credentials['email_verification_code'] = Str::random(50);
        $credentials['password'] = bcrypt($credentials['password']);
        unset($credentials['password_confirmation']);
        $user = User::create($credentials);
        SendVerificationEmailJob::dispatch($user);
        $message = "Your Account has been created . Please Confirm Your Email";
        return \response(['message'=>$message],201);
    }

    public function emailConfirm(Request $request,$code)
    {
        $user = User::where('email_verification_code',$code)->first();
        if (is_null($user)){
            return \response(['Invalid code'],404);
        }
        $user->setAttribute('email_verified_at',now());
        $user->setAttribute('email_verification_code',null);
        $user->save();
        return response(['Your Email Has Been Confirmed'],200);
    }
}
