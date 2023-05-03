<?php

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\PixController;
use App\Http\Controllers\Api\v1\UserController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::post('add-address/{id}', function (Request $request) {
        $address = new Address();
        // dd($request->all());
        $data = [
            'user_id' => $request->user_id,
            'street' => $request->street,
            'zipcode' => $request->zipcode,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ];
        $address->user_id = $request->user_id;
        $address->street = $request->street;
        $address->zipcode = $request->zipcode;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save($data);
        return $address;
    })->name('add-address');

    Route::patch('/me/{id}', [UserController::class, 'update']);

    // PIX
    Route::post('/pix/charge', [PixController::class, 'createCharge']);
    Route::get('/pix/charge/{id}', [PixController::class, 'getCharge']);
    Route::post('/pix/webhook', [PixController::class, 'webHook']);
});
