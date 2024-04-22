<?php

namespace App\Http\Controllers;

use App\Models\LikesTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class LikesTablesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function ispostliked(string $post_id)
    {
        try {
            $isLikedByUser = LikesTables::where('post_id', $post_id)
                           ->where('user_id', Auth::id())
                           ->exists();
            return response()->json(['status_code'=>201, 'data'=>$isLikedByUser,'message'=>'success'], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message'=>'failed'], 404);
        }
    }
    public function likestotal(string $post_id)
    {
        try {
            $Blogposts = LikesTables::where('post_id', $post_id)->count();
            return response()->json(['status_code'=>201, 'data'=>$Blogposts,'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message'=>'failed'], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'post_id'=>'required|max:255',
        ]);
        if($validator->fails()) {return response()->json(['status_code'=>400, 'data'=>null, 'message'=>['errors' => $validator->errors()]],400);}
        try {
        $Blogposts = LikesTables::create([
            'user_id'=> Auth::id(),
            'post_id'=> $request->post_id,
        ]);
        return response()->json(['status_code'=>201, 'data'=>$Blogposts,'message'=>'success'], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null,'message' => 'Unable to like post'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LikesTables $Likes_table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LikesTables $Likes_table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LikesTables $Likes_table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $like_id)
    {
        try {
            $Blogposts = LikesTables::where('like_id',$like_id)->delete();
            return response()->json(['status_code'=>200, 'data'=>null,'message'=>'delete successful'], 200);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null,'message' => 'unable to delete likes'], 404);
        }
    }
}
