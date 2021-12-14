<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ql_matongController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
Route::get('admin/sanpham', [AdminController::class, 'sanpham']);
Route::get('admin/customer', [AdminController::class, 'customer']);

Route::post('admin/products', [AdminController::class, 'newProduct']);
Route::get('admin/categories', [AdminController::class, 'category']);
Route::get('admin/brands', [AdminController::class, 'brand']);
Route::post('admin/login', [AdminController::class, 'login']);
Route::delete('admin/products/{id}', [AdminController::class, 'destroy']);
Route::delete('admin/customer/{id}', [AdminController::class, 'destroy_1']);
Route::get('admin/products/{id}', [AdminController::class, 'ctProduct']);
Route::put('admin/products/{id}', [AdminController::class, 'updateProduct']);

// 
Route::get('product', [ql_matongController::class, 'product']);
Route::get('brand', [ql_matongController::class, 'thuonghieu']);
Route::get('brand/{id}', [ql_matongController::class, 'sptheoth']);
Route::get('category', [ql_matongController::class, 'danhmuc']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('product/{id}', [ql_matongController::class, 'ctProduct']);
Route::get('category/{id}', [ql_matongController::class, 'sptheodm']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::get('admin/bill', [AdminController::class, 'bill']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('addCart', [ql_matongController::class, 'addgiohang']);
    Route::delete('delete_cart', [ql_matongController::class, 'xoacart']);
    Route::delete('delete_all', [ql_matongController::class, 'xoatatcagh']);
    Route::get('listCart', [ql_matongController::class, 'danhsachcart']);
    Route::post('pay', [ql_matongController::class, 'thanhtoan']);
    Route::get('profile', [ql_matongController::class, 'thongtin']);
});
