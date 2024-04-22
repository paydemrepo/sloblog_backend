<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BlogpostsController;
use App\Http\Controllers\LikesTablesController;
use App\Http\Controllers\PostcommentsController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/
Route::post('/register', [AuthenticationController::class, "register"]);
Route::post('/login', [AuthenticationController::class, "login"]);
Route::get('/allpost', [BlogpostsController::class, "allpost"]);
Route::get('/getpost/{post_id}', [BlogpostsController::class, "getpost"]);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->group(function () {
    Route::get('/userinfo/{user_id}', [UserController::class, "showUser"]);
    Route::post('/logout', [AuthenticationController::class, "logout"]);
    Route::post('/changepassword', [AuthenticationController::class, "changepassword"]);

    Route::prefix('blog')->group(function () {
        Route::resource('/post', BlogpostsController::class);
        Route::get('/postbyuser', [BlogpostsController::class, "postByUser"]);
        Route::post('/update/{post_id}', [BlogpostsController::class, "postUpdate"]);
        Route::post('/createnpublish', [BlogpostsController::class, "storeNpublish"]);
        Route::post('/publishpost/{post_id}', [BlogpostsController::class, "publishPost"]);
        Route::post('/unpublishpost/{post_id}', [BlogpostsController::class, "unpublishPost"]);
        Route::get('/totalpost', [BlogpostsController::class, "countPosts"]);
        Route::resource('/comment', PostcommentsController::class);
        Route::get('/countcomment/{post_id}', [PostcommentsController::class, "countcomment"]);
        Route::resource('/likes', LikesTablesController::class);
        Route::get('/likestotal/{post_id}', [LikesTablesController::class,"likestotal"]);
        Route::get('/ispostliked/{post_id}', [LikesTablesController::class,"ispostliked"]);
    });

});


