<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;


Route::get('/', [AuthController :: class, 'login']);
Route::post('login_post', [AuthController::class, 'login_post']);

Route::get('forgot-password', [AuthController::class, 'forgot']);
Route::post('forgot_post', [AuthController :: class, 'forgot_post']);

Route::get('reset/{token}', [AuthController :: class, 'resetPassword']);
Route::post('reset/{token}', [AuthController :: class, 'resetPasswordPost']);
Route::group(['middleware' => 'admin'], function () {
    //dashboard
    Route::get('admin/dashboard', [DashboardController :: class, 'dashboard']);
    //admin
    Route::get('admin/users', [UserController :: class, 'index']);
    Route::get('admin/users/create', [UserController :: class, 'create']);
    Route::post('admin/users/create', [UserController :: class, 'store']);
    Route::get('admin/users/show/{id}', [UserController :: class, 'show']);
    Route::get('admin/users/edit/{id}', [UserController :: class, 'edit']);
    Route::post('admin/users/edit/{id}', [UserController :: class, 'update']);
    Route::get('admin/users/delete/{id}', [UserController :: class, 'destroy']);
});

Route::get('logout', [AuthController :: class, 'logout']);