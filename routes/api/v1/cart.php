<?php

use App\Http\Controllers\Api\v1\CartController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/cart', CartController::class);
