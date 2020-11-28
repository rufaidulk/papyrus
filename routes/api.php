<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\Admin\TagController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\TopicController;
use App\Http\Controllers\Api\Admin\SubjectController;

/*
|--------------------------------------------------------------------------
| Admin API Routes
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

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('topics', TopicController::class);
    Route::apiResource('tags', TagController::class);
});

//todo: need to refactor
Route::get( '/sanctum-dummy-url', function () {
    return response()->json([
        'status' => Response::HTTP_UNAUTHORIZED,
        'message' => 'Unauthenticated.'
    ], Response::HTTP_UNAUTHORIZED);
})->name('login');

Route::fallback(function () {
    return response()->json([
        'status' => Response::HTTP_NOT_FOUND,
        'message' => 'Not Found.'
    ], Response::HTTP_NOT_FOUND);
})->name('api.fallback.404');