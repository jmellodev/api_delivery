<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;

Route::apiResource('/users', UserController::class);
