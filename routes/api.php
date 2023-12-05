<?php

use App\Http\Controllers\API\V1\CustomerController;
use App\Http\Controllers\API\V1\InvoiceController;
use App\Http\Controllers\API\V1\CompanyController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\MemberController;
use App\Http\Controllers\API\V1\JobController;
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


Route::group(['prefix'=> 'v1'],function(){
    Route::apiResource('customer', CustomerController::class)->except('edit', 'create');
    Route::apiResource('invoice', InvoiceController::class)->except('edit', 'create');
    Route::apiResource('company', CompanyController::class)->except('edit', 'create');
    Route::apiResource('user', UserController::class)->except('edit', 'create');
    Route::apiResource('job', JobController::class)->except('edit', 'create');
    Route::post('invoices/bulk',[InvoiceController::class,'bulkStore']);

    Route::group(['prefix'=> 'users'],function(){
        
        
        
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthController::class,'register']);
        Route::post('login', [AuthController::class,'login']);
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('refresh', [AuthController::class,'refresh']);
        Route::post('me', [AuthController::class,'me']);
        Route::post('token', [RefreshTokenController::class,'store']);
    
    });

    Route::group(['prefix'=> 'admin'],function(){
        Route::patch('update/role/{id}',[UserController::class,'updateRole']);
    });

    Route::group(['prefix'=> 'job'],function(){
        Route::get('list/{companyID}',[JobController::class,'listJob']);
    });

    Route::group(['prefix'=>'manager'],function(){
        Route::get('manager',[CompanyController::class,'myCompany']);
    });
    
    Route::group(['prefix'=> 'company'],function(){
        Route::get('job/{companyID}',[CompanyController::class,'listJob']);
        Route::get('follower/{id}',[CompanyController::class,'listFollowers']);
        Route::get('following/list',[CompanyController::class,'listFollowing']);
        Route::post('following/{companyID}',[CompanyController::class,'addFollow']);
        Route::delete('following/{companyID}',[CompanyController::class,'unFollow']);
        
    });
    Route::group([
        'prefix'=>'member'
    ],function(){
        Route::get('{companyID}/{userID}',[MemberController::class,'isMember']);
        Route::get('{companyID}',[MemberController::class,'listMember']);
        Route::post('{companyID}/{userID}',[MemberController::class,'addMember']);
        Route::delete('{userID}',[MemberController::class,'removeMember']);
    
    });
    
    
});











