<?php

namespace App\Http\Controllers;

use App\Models\PostComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;



class PostcommentsController extends Controller
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'post_id'=>'required|max:255',
            'comment'=>'required',
        ]);
        if($validator->fails()) {return response()->json(['status_code'=>400, 'data'=>null, 'message'=>['errors' => $validator->errors()]],400);}
        try {
        $Blogposts = PostComments::create([
            'post_id'=>$request->post_id,
            'user_id'=>Auth::id(),
            'comment'=>$request->comment
        ]);
        return response()->json(['status_code'=>201, 'data'=>$Blogposts, 'message' => 'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Unable to post comment'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PostComments $comments, string $post_id)
    {
        try {
            $Blogposts = PostComments::where('post_id', $post_id)->orderBy('created_at', 'desc')->get();
            return response()->json(['status_code'=>201, 'data'=>$Blogposts, 'message'=>'success'], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Blogposts not found'], 404);
        }
    }

    public function countcomment(PostComments $comments, string $post_id)
    {
        try {
            $Blogposts = PostComments::where('post_id', $post_id)->count();
            return response()->json(['status_code'=>201, 'data'=>$Blogposts, 'message'=>'success'], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message'=>'failed'], 404);
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostComments $comments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostComments $comments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $comment_id)
    {
        try {
            $Blogposts = PostComments::where('comment_id',$comment_id)->delete();
            return response()->json(['status_code'=>201, 'data'=>null, 'message'=>'delete sucessful'], 200);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' =>'unable to delete comment'], 404);
        }
    }
}
