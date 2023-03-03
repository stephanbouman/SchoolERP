<?php

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\EnquiryPublicController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public
Route::post("/public/enquiry", [EnquiryPublicController::class, 'publicStore'])->name('api.public.enquiry');
