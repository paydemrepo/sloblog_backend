<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $request->validate(
            ['email'=>'required|max:255',
            'password'=>'required|min:6',
            ]);
        $user = User::where('email', $request->email)->first();
        if(!$user|| !Hash::check($request->password, $user->password)){
            return response()->json(['status_code'=>401, 'data'=>null, 'message' => 'Unable to login'], 401);
        }
        $token = $user->createToken('auth_token')->accessToken;
            return response()->json(['status_code'=>201, 'data'=>['token' => $token,'name' => $user->name], 'message'=>'login successful'], 201);
        }
    public function register(Request $request){
            $validator = Validator::make($request->all(), [
            'full_name'=>'required|max:255',
            'password'=>'required|min:6',
            'email'=>'required|max:255|unique:users,email'
            ]);
            if($validator->fails()) {return response()->json(['status_code'=>400, 'data'=>null, 'message'=>['errors' => $validator->errors()]],400);}
            $user = User::create(
                [
                'name'=>$request->full_name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                ]
            );
            return response()->json(['status_code'=>201, 'data'=>$user,'message'=>'success'],201);      
    }
    public function changepassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|min:8',
        ]);
        if($validator->fails()) {return response()->json(['status_code'=>400, 'data'=>null, 'message'=>['errors' => $validator->errors()]],400);}
        if($request->new_password === $request->confirm_new_password && Hash::check($request->old_password, Auth::user()->password)){
            try {
            $getUser = User::where('email', Auth::user()->email)->update(['password'=>Hash::make($request->new_password)]);
        if($getUser){
            return response()->json(['status_code'=>200, 'data'=>null,'message' => 'password changed successfuly'], 200);
        }else{
            return response()->json(['status_code'=>404, 'data'=>null,'message' => 'Unable to change password'], 404);
        }
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null,'message' => 'Unable to change password'], 404);
        }
        }else{
            return response()->json(['status_code'=>404, 'data'=>null,'message' => 'Password change failed'], 404);
        }  
    }
    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json(['status_code'=>405, 'data'=>null,'message' => 'logout Successful'], 405);
    }
}
