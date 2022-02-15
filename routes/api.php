<?php

use App\Http\Controllers\ReviewAnswerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\Review;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    // добавить нового пользователя с ролью Writer
    Route::post('/users/writer', [UserController::class, 'createWriter']);
    // удалить пользователя
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

    Route::get('/reviews', function () {
        return Review::paginate(5);
    });

//    Route::get('/reviews', [ReviewController::class, 'reviews']);
    Route::get('/reviews/{id}', [ReviewController::class, 'oneReview']);
    Route::post('/reviews', [ReviewController::class, 'createReview']);
    Route::put('/reviews/{id}', [ReviewController::class, 'updateReview']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'deleteReview']);

    Route::post('/reviews/{id}/answer', [ReviewAnswerController::class, 'createAnswer']);
});
