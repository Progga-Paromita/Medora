<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\MedicinesController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\PurchasesController;


Route::get('/', [AuthController::class, 'login']);
Route::post('login_post', [AuthController::class, 'login_post']);

Route::get('forgot-password', [AuthController::class, 'forgot']);
Route::post('forgot_post', [AuthController::class, 'forgot_post']);

Route::get('reset/{token}', [AuthController::class, 'resetPassword']);
Route::post('reset/{token}', [AuthController::class, 'resetPasswordPost']);

Route::group(['middleware' => 'admin'], function () {

    // dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);

    // users
    Route::get('admin/users', [UserController::class, 'index']);
    Route::get('admin/users/create', [UserController::class, 'create']);
    Route::post('admin/users/create', [UserController::class, 'store']);
    Route::get('admin/users/show/{id}', [UserController::class, 'show']);
    Route::get('admin/users/edit/{id}', [UserController::class, 'edit']);
    Route::post('admin/users/edit/{id}', [UserController::class, 'update']);
    Route::get('admin/users/delete/{id}', [UserController::class, 'delete']);

    // customers
    Route::get('admin/customers', [CustomersController::class, 'index']);
    Route::get('admin/customers/create', [CustomersController::class, 'create']);
    Route::post('admin/customers/create', [CustomersController::class, 'store']);
    Route::get('admin/customers/edit/{id}', [CustomersController::class, 'edit']);
    Route::post('admin/customers/edit/{id}', [CustomersController::class, 'update']);
    Route::get('admin/customers/delete/{id}', [CustomersController::class, 'delete']);

    // medicines
    Route::get('admin/medicines', [MedicinesController::class, 'list']);
    Route::get('admin/medicines/create', [MedicinesController::class, 'create']);
    Route::post('admin/medicines/create', [MedicinesController::class, 'store']);
    Route::get('admin/medicines/edit/{id}', [MedicinesController::class, 'edit']);
    Route::post('admin/medicines/edit/{id}', [MedicinesController::class, 'update']);
    Route::get('admin/medicines/delete/{id}', [MedicinesController::class, 'delete']);

    // suppliers
    Route::get('admin/suppliers', [SuppliersController::class, 'list']);
    Route::get('admin/suppliers/create', [SuppliersController::class, 'create_suppliers']);
    Route::post('admin/suppliers/create', [SuppliersController::class, 'insert_suppliers']);
    Route::get('admin/suppliers/edit/{id}', [SuppliersController::class, 'edit_suppliers']);
    Route::post('admin/suppliers/edit/{id}', [SuppliersController::class, 'update_suppliers']);
    Route::get('admin/suppliers/delete/{id}', [SuppliersController::class, 'delete_suppliers']);

    // stocks
    Route::get('admin/stocks', [MedicinesController::class, 'list_stock']);
    Route::get('admin/stocks/create', [MedicinesController::class, 'add_stock']);
    Route::post('admin/stocks/create', [MedicinesController::class, 'store_stock']);
    Route::get('admin/stocks/edit/{id}', [MedicinesController::class, 'edit_stock']);
    Route::post('admin/stocks/edit/{id}', [MedicinesController::class, 'update_stock']);
    Route::get('admin/stocks/delete/{id}', [MedicinesController::class, 'delete_stock']);

// Invoices
Route::get('admin/invoices', [InvoicesController::class, 'list']);
Route::get('admin/invoices/create', [InvoicesController::class, 'add']);
Route::post('admin/invoices/create', [InvoicesController::class, 'store']);
Route::get('admin/invoices/edit/{id}', [InvoicesController::class, 'edit']);
Route::post('admin/invoices/edit/{id}', [InvoicesController::class, 'update']);
Route::get('admin/invoices/delete/{id}', [InvoicesController::class, 'delete']);


// Purchase Invoices
Route::prefix('admin/purchases/')->group(function () {
    Route::get('', [PurchasesController::class, 'purchases']);
    Route::get('add', [PurchasesController::class, 'addPurchase']);
    Route::post('add', [PurchasesController::class, 'storePurchase']);
    Route::get('edit/{id}', [PurchasesController::class, 'editPurchase']);
    Route::post('edit/{id}', [PurchasesController::class, 'updatePurchase']);
    Route::get('delete/{id}', [PurchasesController::class, 'deletePurchase']);
});



});

Route::get('logout', [AuthController::class, 'logout']);
