<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Blogposts;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class BlogpostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Blogposts = Blogposts::orderBy('created_at', 'desc')->get();
            $Blogposts = json_decode($Blogposts,true);
            $nBlogposts = []; 
            foreach($Blogposts as $xPost){
                $xPost['author'] = User::where('id', $xPost['user_id'])->firstOrFail()->name;
                array_push($nBlogposts,$xPost);
            }
            return response()->json(['status_code'=>201, 'data'=>$nBlogposts, 'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Blogposts not found'], 404);
        }
    }
public function allpost()
    {
        try {
            $Blogposts = Blogposts::orderBy('created_at', 'desc')->get();
            $Blogposts = json_decode($Blogposts,true);
            $nBlogposts = []; 
            foreach($Blogposts as $xPost){
                $xPost['author'] = User::where('id', $xPost['user_id'])->firstOrFail()->name;
                array_push($nBlogposts,$xPost);
            }
            return response()->json(['status_code'=>201, 'data'=>$nBlogposts, 'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Blogposts not found'], 404);
        }
    }
    public function postByUser()
    {
        try {
            $Blogposts = Blogposts::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
            $nBlogposts = []; 
            foreach($Blogposts as $xPost){
                $xPost['author'] = User::where('id', $xPost['user_id'])->firstOrFail()->name;
                array_push($nBlogposts,$xPost);
            }
            return response()->json(['status_code'=>201, 'data'=>$nBlogposts,'message'=>'success'], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Blog posts by user not found'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'title'=>'required|unique:blogposts,title',
            'description'=>'required',
            'img_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload
            'content'=>'required',
        ]);
        if($validator->fails()) {return response()->json(['status_code'=>400, 'data'=>null, 'message'=>['errors' => $validator->errors()]],400);}

        try {

            // $attributes = [
            //     'user_id'=>Auth::id(),
            //     'title'=>$request->title,
            //     'description'=>$request->description,
            //     'content'=>$request->content,
            // ];

            // if ($request->hasFile('img_url')) {
            //     $path = $request->file('img_url')->store('public/blog_images');
            //     $attributes['img_url'] = basename($path); // Save only the filename
            // }
            // $Blogposts = Blogposts::create($attributes);
            $Blogposts = Blogposts::create([
                'user_id'=>Auth::id(),
                'title'=>$request->title,
                'description'=>$request->description,
                'content'=>$request->content,
            ]);
            if ($request->hasFile('img_url')) {
                $path = $request->file('img_url')->store('public/blog_images');
                $Blogposts->img_url = Storage::url($path); // Store the URL for access in the app
                $Blogposts->save(); // Save the changes to the model
            }
        return response()->json(['status_code'=>201, 'data'=>$Blogposts, 'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Unable to create Post'], 404);
        }
    }
    public function storeNpublish(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'title'=>'required|unique:blogposts,title',
            'description'=>'required',
            'img_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload
            'content'=>'required',
        ]);
        if($validator->fails()) {return response()->json(['status_code'=>400, 'data'=>null, 'message'=>['errors' => $validator->errors()]],400);}

        try {
        $Blogposts = Blogposts::create([
            'user_id'=>Auth::id(),
            'title'=>$request->title,
            'description'=>$request->description,
            'content'=>$request->content,
            'isPublished'=>true,
        ]);

        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('public/blog_images');
            $Blogposts->img_url = Storage::url($path); // Store the URL for access in the app
            $Blogposts->save(); // Save the changes to the model
        }
    
        return response()->json(['status_code'=>201, 'data'=>$Blogposts,'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Unable to create Post'], 404);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Blogposts $blogposts, string $post_id)
    {
        
        try {
            $Blogposts = Blogposts::findOrFail($post_id);
            $nBlogposts = json_decode($Blogposts,true);
            $nBlogposts['author'] = User::where('id', $Blogposts->user_id)->firstOrFail()->name;
            return response()->json(['status_code'=>201, 'data'=>$nBlogposts,'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Blogposts not found'], 404);
        }
    }
    public function getpost(Blogposts $blogposts, string $post_id)
    {
        
        try {
            $Blogposts = Blogposts::findOrFail($post_id);
            $nBlogposts = json_decode($Blogposts,true);
            $nBlogposts['author'] = User::where('id', $Blogposts->user_id)->firstOrFail()->name;
            return response()->json(['status_code'=>201, 'data'=>$nBlogposts,'message'=>'success'],201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Blogposts not found'], 404);
        }
    }
    public function publishPost(Blogposts $blogposts, string $post_id)
    {
        try {
            $Blogposts = Blogposts::where('post_id', $post_id)->firstOrFail();
            $Blogposts = $Blogposts->update(
                [
                    'isPublished'=>true,
                ]
        );
        if($Blogposts){
            return response()->json(['status_code'=>201, 'data'=>$Blogposts, 'message' => 'Post has been published'], 201); 
        }else{
            return response()->json(['status_code'=>500, 'data'=>null, 'message' => 'Unable to publish Post'], 500);  
        }
        

        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Post not found'], 404);
        }
    }
       public function unpublishPost(Blogposts $blogposts, string $post_id)
    {
        try {
            $Blogposts = Blogposts::where('post_id', $post_id)->firstOrFail();
            $Blogposts = $Blogposts->update(
                [
                    'isPublished'=>false,
                ]
        );
        if($Blogposts){
            return response()->json(['status_code'=>201, 'data'=>$Blogposts, 'message' => 'Post has been unpublished'], 201); 
        }else{
            return response()->json(['status_code'=>500, 'data'=>null, 'message' => 'Unable to unpublish Post'], 500);  
        }
        

        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Post not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function countPosts(){
        $post_count = BlogPosts::where('user_id', Auth::id())->count();
        return response()->json(['status_code'=>201, 'data'=>$post_count, 'message' => $post_count.' Post'], 201); 

    }
        public function update(Request $request,string $post_id){
        
    }
    public function postUpdate(Request $request,string $post_id)
    {
        $validator = Validator::make($request->all(), 
        [
            'title'=>'required',
            'description'=>'required',
            'img_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload
            'content'=>'required',
        ]);
        try {
            $Blogposts = Blogposts::where('post_id', $post_id)->firstOrFail();
            $BlogpostsUpdate = $Blogposts->update([
                'title'=>$request->title,
                'description'=>$request->description,
                'content'=>$request->content,
            ]);
            if ($request->hasFile('img_url')) {
                $path = $request->file('img_url')->store('public/blog_images');
                $Blogposts->img_url = Storage::url($path); // Store the URL for access in the app
                $Blogposts->save(); // Save the changes to the model
            }
            return response()->json(['status_code'=>201, 'data'=>$BlogpostsUpdate, 'message'=>'Update Successful'], 201);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message'=>'Update Failed'], 404);
        }
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function edit(Request $request, Blogposts $blogposts)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blogposts $blogposts, string $post_id)
    {
        try {
            $Blogposts = Blogposts::where('post_id',$post_id)->delete();
            return response()->json(['status_code'=>200, 'data'=>null, 'message'=>'Delete successful'], 200);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the ID is not found
            return response()->json(['status_code'=>404, 'data'=>null, 'message' => 'Unable to delete Post'], 404);
        }
    }
}
