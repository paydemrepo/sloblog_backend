<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function showUser(User $User, string $user_id)
    {
        
        try {
            $Blogposts = User::findOrFail($user_id);
            return response()->json(['status_code'=>200, 'data'=>$Blogposts, 'message' => 'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'user not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyUser(User $User, string $user_id)
    {
        try {
            $User = User::where('user_id',$user_id)->delete();
            return response()->json(['status_code'=>200, 'data'=>null, 'message'=>'delete sucessful'], 200);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'unable to delete user'], 404);
        }
    }
}
