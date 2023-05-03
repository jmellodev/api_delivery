<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GeoLocationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('get-address-from-ip', [GeoLocationController::class, 'index']);
