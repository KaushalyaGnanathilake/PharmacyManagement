<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/customer/login', 'Auth\CustomerLoginController@login')->name('customer.login.submit');

Route::namespace('App\Http\Controllers\Admin')->prefix('admin')->name('admin.')->middleware('can:owner_and_manager')->group(function(){
    Route::resource('/users', 'UserController',['except' => ['show','create','store']]);
});

Route::namespace('App\Http\Controllers\Customer')->prefix('customer')->name('customer.')->middleware('can:owner_and_manager')->group(function(){
    Route::resource('/customers', 'CustomerController',['except' => ['show','create','edit']]);
});

Route::namespace('App\Http\Controllers\Item')->prefix('item')->name('item.')->middleware('can:owner_and_cashier')->group(function(){
    Route::resource('/items', 'ItemController',['except' => ['show','create','edit']]);
});

Route::namespace('App\Http\Controllers\Bill')->prefix('bill')->name('bill.')->middleware('can:owner_and_cashier')->group(function(){
    Route::resource('/bills', 'BillController',['except' => ['show','create','edit']]);
});

Route::namespace('App\Http\Controllers\Order')->prefix('order')->name('order.')->group(function(){
    Route::resource('/orders', 'OrderController',['except' => ['show','create','edit']]);
});


