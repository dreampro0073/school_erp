<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', [UserController::class,'login'])->name("login");
Route::get('/error',function(){
	return view('error');
});



Route::get('/logout',function(){
	Auth::logout();
	return Redirect::to('/login');
});

Route::get('/',[AdminController::class,'dashboard']);

Route::group(['middleware'=>'auth'],function(){	
	Route::group(['prefix'=>"admin"], function(){		
		Route::get('/dashboard',[AdminController::class,'dashboard']);	
	});
});

Route::group(['prefix'=>"api"], function(){	
	
});
