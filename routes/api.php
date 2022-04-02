<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PaymentRecordController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\SubCategoryController;
use App\Http\Controllers\Api\V1\TransactionController;
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

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1'], function() {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/profile', function (Request $request) {
            return response()->success($request->user());
        });

        Route::group(['prefix' => 'admin', 'middleware' => 'ability:admin-apis'], function () {
            Route::post('/categories', [CategoryController::class, 'create']);
            Route::post('/sub-categories', [SubCategoryController::class, 'create']);

            Route::get('/transactions', [TransactionController::class, 'index']);
            Route::post('/transactions', [TransactionController::class, 'create']);

            Route::get('/payment-records', [PaymentRecordController::class, 'index']);
            Route::post('/payment-records', [PaymentRecordController::class, 'create']);

            Route::get('/monthly-report', [reportController::class, 'monthlyReport']);
        });

        Route::group(['prefix' => 'user', 'middleware' => 'ability:user-apis'], function () {
            Route::get('/my-transactions', [TransactionController::class, 'myTransactions']);
        });
    });

});
