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


//Route::group(['middleware' => ['auth:api','jwt.refresh']], function(){
Route::group(['middleware' => 'auth:api'], function(){
    Route::get('products', function () {
        $products = \App\Product::all();
        return \App\Http\Resources\ProductResource::collection($products);
    });

    Route::get('products/{product}', function (\App\Product $product) {
        return new \App\Http\Resources\ProductResource($product);
    });
});

Route::post('login', function (Request $request) {
    $data = $request->only('email', 'password');
    $token = \Auth::guard('api')->attempt($data);
    if (!$token) {
        return response()->json([
            'error' => 'Credentials invalid'
        ], 400);
    }
    return ['token' => $token];
});