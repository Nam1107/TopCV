<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('home')->group(function () {
    Route::get('news/{slug?}/{id?}', function ($id = null, $slug = null) {
        $content = 'Content';
        $content .= ' id = ' . $id . ' slug = ' . $slug;
        return $content;
    });
    Route::get('main/{slug}-{id}', function ($slug = null, $id = null) {
        $content = 'Content';
        $content .= ' id = ' . $id . ' slug = ' . $slug;
        return $content;
    })->where('slug', '.+')->where('id', '[0-9]+');
    // ->where(
    //     [
    //         'slug' => '.+',
    //         'id' => '[0-9]+'
    //     ]
    // );

});
//middleware
Route::get('test', function () {
    echo 1;
    die;
})->name('test');
Route::prefix('admin')->middleware('checkpermission')->group(function () {
    Route::get('news/{slug?}/{id?}', function ($id = null, $slug = null) {
        $content = 'Content';
        $content .= ' id = ' . $id . ' slug = ' . $slug;
        return $content;
    });
    Route::get('home/{slug}-{id}', function ($slug = null, $id = null) {
        $content = 'Content';
        $content .= ' id = ' . $id . ' slug = ' . $slug;
        return $content;
    })->where('slug', '.+')->where('id', '[0-9]+');
    // ->where(
    //     [
    //         'slug' => '.+',
    //         'id' => '[0-9]+'
    //     ]
    // );

});

Route::get('index', 'App\Http\Controllers\TestController@index');

use App\Http\Controllers\TestController;

Route::get('chuyen-muc/{id?}', [TestController::class, 'getCategory']);

Route::get('setup',function(){
    $cred = [
        'email'=>'admin@gmail.com',
        'password'=>'12345678',
    ];
    if(!Auth::attempt($cred)){
        $user = new User();
        $user->name = 'Admin';
        $user->email=$cred['email'];
        $user->password = Hash::make($cred['password']);
        $user->save();

        if(Auth::attempt($cred)){
            // $user = Auth::user();
            $adminToken = $user->createToken('admin-token',['create','update','delete']);
            $updateToken = $user->createToken('update-token',['create','update']);
            $basicToken = $user->createToken('basic-token');

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken
            ];
        }
        
    }

});