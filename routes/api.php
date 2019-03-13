<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});


Route::post('image', function (Request $request) {
    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
    $file = $request->file('image');
    Storage::drive('avatars')->putFile('photossss', $file);
//    $paths = array();
//
//    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $image */
//    foreach ($request->image as $image) {
//        $paths[] = $image->store('images');
//    }
//
//array(
//    'code'=>1,
//    'message'=>"Error message"
//);
//
//    //return response()->json(array('code'=>1, 'message'=>"Error message"))->setStatusCode(401);
//    return response()->json($paths)->setStatusCode(200);

    return response()->json(['success' => true])->setStatusCode(200);
});