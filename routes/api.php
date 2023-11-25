<?php

use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\API\V1\InvoiceController;
use App\Http\Controllers\API\V1\CompanyController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\RefreshTokenController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::resource('company', CompanyController::class)->except('edit', 'create');

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('customers', CustomerController::class)->except('edit', 'create');
    Route::apiResource('invoices', InvoiceController::class)->except('edit', 'create');
    Route::apiResource('company', CompanyController::class)->except('edit', 'create');
    Route::apiResource('users', UserController::class)->except('edit', 'create');
    Route::post('invoices/bulk',[InvoiceController::class,'bulkStore']);
    Route::patch('add-follow/{id}',[CompanyController::class,'addFollow']);
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class,'login']);

Route::group([

    'prefix' => 'auth'

], function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('token', [RefreshTokenController::class,'store']);

});


