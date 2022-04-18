<?php

use App\Http\Controllers\API\category;
use App\Http\Controllers\API\chat\follow_controller;
use App\Http\Controllers\API\chat\frend_controller;
use App\Http\Controllers\API\chat\GroupChat_Controller;
use App\Http\Controllers\API\chat\Message_controller;
use App\Http\Controllers\API\Group\Group_controller;
use App\Http\Controllers\API\Group\invite_group_controller;
use App\Http\Controllers\API\post\comment_controller as APIComment_controller;
use App\Http\Controllers\API\post\like_post_cotroller as  APIlike_post_cotroller;
use App\Http\Controllers\API\post\PostController;
use App\Http\Controllers\API\post\save_post_controller;
use App\Http\Controllers\API\post\share_post_controller;
use App\Http\Controllers\API\profile\album_controller;
use App\Http\Controllers\API\profile\profile_controller;
use App\Http\Controllers\API\trip\accommodation_controller;
use App\Http\Controllers\API\trip\CarRental_controller;
use App\Http\Controllers\API\trip\flight_controller;
use App\Http\Controllers\API\trip\place_visite_controller;
use App\Http\Controllers\API\trip\restouant_controller;
use App\Http\Controllers\API\trip\transportation_controller;
use App\Http\Controllers\API\trip\trip_controller;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RestePasswordController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('city', [Controller::class, 'get_city']);
Route::group(['middleware' =>'api'] , function(){
  // Auth
  Route::post('login' , [AuthController::class , 'login']) ;
  Route::post('register' , [AuthController::class , 'register']) ;
  // forget password
  Route::post('forget' ,[ RestePasswordController::class, 'forget_password']) ;
  Route::post('check_code' , [RestePasswordController::class , 'check_code']) ;
  Route::post('reset_password' , [RestePasswordController::class , 'reset_password']) ;
   // end forget password

   // login with facebook
    Route::prefix('facebook')->name('facebook.')->group( function(){
      Route::get('auth', [FaceBookController::class, 'loginUsingFacebook'])->name('login');
      Route::get('callback', [FaceBookController::class, 'callbackFromFacebook'])->name('callback');
    });
    // end login with facebook

    // login with google
    Route::prefix('google')->name('google.')->group( function(){
        Route::get('login', [GoogleController::class, 'loginWithGoogle'])->name('login');
        Route::any('callback', [GoogleController::class, 'callbackFromGoogle'])->name('callback');
    });
    // end login with google


  Route::group(['middleware' =>'check:api'] , function(){

    Route::post('logout' , [AuthController::class , 'logout']);

    // end Auth

    // post

    Route::resource('post' , PostController::class)->only('index' , 'store' , 'show' , 'update' , 'destroy');
    // comment  inh post
    Route::resource('comment' , APIComment_controller::class)->only( 'store' , 'show' , 'update' , 'destroy');
    // like in post
    Route::post('add_like' , [APIlike_post_cotroller::class , 'add_like']);
    Route::post('remove_like/{id}' , [APIlike_post_cotroller::class , 'delete_like']);

    # save post
    Route::post('save_post' , [save_post_controller::class , 'save_post']);
    Route::post('unsave_post/{id}' , [save_post_controller::class , 'unsave_post']);

    # share post
    Route::post('share_post' , [share_post_controller::class , 'save_share_post']);
    Route::post('unshare_post' , [share_post_controller::class , 'unsave_share_post']);

    // video
    Route::post('video' , [PostController::class , 'save_video']);

    // group
    Route::post('create_group' , [Group_controller::class , 'create_group']);
    Route::post('join_group' , [Group_controller::class , 'join_group']);
    Route::get('get_group_info' , [Group_controller::class , 'get_group_public']);
    Route::get('get_group/{id}' , [Group_controller::class , 'get_group_with_user_and_post']);

    Route::post('invite_user' , [invite_group_controller::class , 'invite_user']);
    Route::post('accept_invite/{id}' , [invite_group_controller::class , 'accept_invite']);
    Route::post('not_accept_invite/{id}' , [invite_group_controller::class , 'not_accept_invite']);

    // end group


    // end post

    // chat
        // frend
          Route::get('frends/{id}' , [frend_controller::class , 'frend']);
          Route::post('friend_request' , [frend_controller::class , 'friend_request']);
          Route::post('accept_friend_request/{id}' , [frend_controller::class , 'accept_friend_request']);
          Route::get('frind_request_get/{id}' , [frend_controller::class , 'frind_request_get']);
          Route::delete('delete_friend/{id}' , [frend_controller::class , 'delete_friend_request']);

        // end frend

        // follow
            Route::post('follow_to' , [follow_controller::class , 'follow_to']);
            Route::post('accept_follow/{id}' , [follow_controller::class , 'accept_follow']);
            Route::post('not_accept_follow/{id}' , [follow_controller::class , 'not_accept_follow']);
            Route::get('get_follow/{id}' , [follow_controller::class , 'get_follow']);

        // end follow
        // chat

        //message
        Route::post('save_message' , [Message_controller::class , 'save_mesage']);
        Route::get('message_room/{id}' , [Message_controller::class , 'get_message_room']);
        Route::delete('delete_message/{id}' , [Message_controller::class , 'delete_message']);
        //end message
        // create group
        Route::post('make_group' , [GroupChat_Controller::class , 'make_group']) ;
        Route::post('add_user_to_group' , [GroupChat_Controller::class , 'add_user_to_group']) ;
        Route::post('leave_group' , [GroupChat_Controller::class , 'leave_group']) ;

        // end group
        Route::get('onlin_users' , [frend_controller::class , 'onlin_users']);

        // end chat




    // end chat


    // trip
    Route::post('create_trip' , [trip_controller::class , 'create_trip']);
    Route::get('get_trip_people/{id}' , [trip_controller::class , 'get_trip_people']);
    Route::get('get_trip_my/{id}' , [trip_controller::class , 'get_trip_my']);
    Route::delete('delete_trip/{tripid}/{userid}' , [trip_controller::class , 'delete_trip']);
    // end trip

    // trip flight
    Route::post('create_flight' , [flight_controller::class , 'create_flight']);
    // end trip flight

    // trip Accommodation
    Route::post('create_accommodation' , [accommodation_controller::class , 'create_accommodation']);
    // end trip Accommodation

    // trip car_Rentel
    Route::post('create_car_rental' , [CarRental_controller::class , 'create_car_rental']);
    // end trip car_Rentel

    // trip Restauant
    Route::post('create_restauant' , [restouant_controller::class , 'create_restauant']);
    // end trip Restauant

    // trip place_visite
    Route::post('create_place_visite' , [place_visite_controller::class , 'create_place_visite']);
    // end trip place_visite

    // trip transportation
    Route::post('create_transportation' , [transportation_controller::class , 'create_transportation']);
    // end trip transportation

    // profile

        Route::get('get_profile/{id}',[ profile_controller::class  , 'get_profile']);
        Route::post('update_profile/{id}', [ profile_controller::class  , 'update_profile']);
        Route::get('get_post_profile/{id}', [ profile_controller::class  , 'get_post_profile']);
        Route::get('get_album_profile/{id}', [ profile_controller::class  , 'get_album_profile']);
        Route::get('get_about_profile/{id}', [ profile_controller::class  , 'get_about_profile']);


    // end profile
    // album
    Route::post('create_album' , [album_controller::class , 'create_album']);
    // end album

  });

});



