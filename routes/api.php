<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

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

Route::prefix('admin')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\Admin\AuthController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\Api\Admin\AuthController::class, 'register']);
    Route::apiResource('subjects', \App\Http\Controllers\Api\Admin\SubjectController::class);
    Route::apiResource('topics', \App\Http\Controllers\Api\Admin\TopicController::class);
    Route::apiResource('tags', \App\Http\Controllers\Api\Admin\TagController::class);
    Route::apiResource('commissions', \App\Http\Controllers\Api\Admin\CommissionController::class);
    Route::apiResource('articles', \App\Http\Controllers\Api\Admin\ArticleController::class);
    Route::apiResource('subscription-plans', \App\Http\Controllers\Api\Admin\SubscriptionPlanController::class);
});

Route::apiResource('articles', \App\Http\Controllers\Api\ArticleController::class);

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