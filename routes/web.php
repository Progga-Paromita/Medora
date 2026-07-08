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
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AdministrationController;

// ================= GUEST ROUTES =================
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('login_post', [AuthController::class, 'login_post']);
    Route::get('forgot-password', [AuthController::class, 'forgot']);
    Route::post('forgot_post', [AuthController::class, 'forgot_post']);
    Route::get('reset/{token}', [AuthController::class, 'resetPassword']);
    Route::post('reset/{token}', [AuthController::class, 'resetPasswordPost']);
});

// ================= AUTHENTICATED ROUTES (Admin & Staff) =================
Route::group(['middleware' => 'auth'], function () {
    // Logout
    Route::get('logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);

    // Profile Management (My Account)
    Route::get('admin/my-account', [DashboardController::class, 'my_account']);
    Route::post('admin/my-account', [DashboardController::class, 'update_account']);

    // Customers (Accessible by both Admin and Staff)
    Route::get('admin/customers', [CustomersController::class, 'index']);
    Route::get('admin/customers/create', [CustomersController::class, 'create']);
    Route::post('admin/customers/create', [CustomersController::class, 'store']);
    Route::get('admin/customers/show/{id}', [CustomersController::class, 'show']);
    Route::get('admin/customers/edit/{id}', [CustomersController::class, 'edit']);
    Route::post('admin/customers/edit/{id}', [CustomersController::class, 'update']);
    Route::get('admin/customers/delete/{id}', [CustomersController::class, 'delete']);
    Route::get('admin/customers/restore/{id}', [CustomersController::class, 'restore']);

    // Medicines (Accessible by both Admin and Staff)
    Route::get('admin/medicines', [MedicinesController::class, 'list']);
    Route::get('admin/medicines/create', [MedicinesController::class, 'create']);
    Route::post('admin/medicines/create', [MedicinesController::class, 'store']);
    Route::get('admin/medicines/show/{id}', [MedicinesController::class, 'show']);
    Route::get('admin/medicines/edit/{id}', [MedicinesController::class, 'edit']);
    Route::post('admin/medicines/edit/{id}', [MedicinesController::class, 'update']);
    Route::get('admin/medicines/delete/{id}', [MedicinesController::class, 'delete']);
    Route::get('admin/medicines/restore/{id}', [MedicinesController::class, 'restore']);

    // Invoices / Sales (Accessible by both Admin and Staff)
    Route::get('admin/invoices', [InvoicesController::class, 'list']);
    Route::get('admin/invoices/create', [InvoicesController::class, 'add']);
    Route::post('admin/invoices/create', [InvoicesController::class, 'store']);
    Route::get('admin/invoices/search-medicine', [InvoicesController::class, 'searchMedicine']);
    Route::get('admin/invoices/show/{id}', [InvoicesController::class, 'show']);
    Route::get('admin/invoices/edit/{id}', [InvoicesController::class, 'edit']);
    Route::post('admin/invoices/edit/{id}', [InvoicesController::class, 'update']);
    Route::get('admin/invoices/delete/{id}', [InvoicesController::class, 'delete']);
    Route::get('admin/invoices/restore/{id}', [InvoicesController::class, 'restore']);
    Route::get('admin/invoices/print/{id}', [InvoicesController::class, 'printInvoice']);

    // Inventory Monitoring (Shared)
    Route::get('admin/inventory/dashboard', [InventoryController::class, 'dashboard']);
    Route::get('admin/inventory/stock', [InventoryController::class, 'listStock']);
    Route::get('admin/inventory/low-stock', [InventoryController::class, 'lowStock']);
    Route::get('admin/inventory/expired', [InventoryController::class, 'expired']);
    Route::get('admin/inventory/near-expiry', [InventoryController::class, 'nearExpiry']);
    Route::get('admin/inventory/history', [InventoryController::class, 'history']);

    Route::get('admin/inventory/notifications', [AdministrationController::class, 'notifications']);
    Route::get('admin/inventory/help', [AdministrationController::class, 'help']);

    // ================= ADMIN-ONLY ROUTES =================
    Route::group(['middleware' => 'admin'], function () {
        // Users (Staff Users Management)
        Route::get('admin/users', [UserController::class, 'index']);
        Route::get('admin/users/create', [UserController::class, 'create']);
        Route::post('admin/users/create', [UserController::class, 'store']);
        Route::get('admin/users/show/{id}', [UserController::class, 'show']);
        Route::get('admin/users/edit/{id}', [UserController::class, 'edit']);
        Route::post('admin/users/edit/{id}', [UserController::class, 'update']);
        Route::get('admin/users/delete/{id}', [UserController::class, 'delete']);
        Route::get('admin/users/restore/{id}', [UserController::class, 'restore']);
        Route::get('admin/users/status/{id}', [UserController::class, 'toggleStatus']);
        Route::post('admin/users/reset-password/{id}', [UserController::class, 'resetPasswordAction']);

        // Suppliers
        Route::get('admin/suppliers', [SuppliersController::class, 'list']);
        Route::get('admin/suppliers/create', [SuppliersController::class, 'create_suppliers']);
        Route::post('admin/suppliers/create', [SuppliersController::class, 'insert_suppliers']);
        Route::get('admin/suppliers/show/{id}', [SuppliersController::class, 'show']);
        Route::get('admin/suppliers/edit/{id}', [SuppliersController::class, 'edit_suppliers']);
        Route::post('admin/suppliers/edit/{id}', [SuppliersController::class, 'update_suppliers']);
        Route::get('admin/suppliers/delete/{id}', [SuppliersController::class, 'delete_suppliers']);
        Route::get('admin/suppliers/restore/{id}', [SuppliersController::class, 'restore']);

        // Stocks (Inventory)
        Route::get('admin/stocks', [MedicinesController::class, 'list_stock']);
        Route::get('admin/stocks/create', [MedicinesController::class, 'add_stock']);
        Route::post('admin/stocks/create', [MedicinesController::class, 'store_stock']);
        Route::get('admin/stocks/edit/{id}', [MedicinesController::class, 'edit_stock']);
        Route::post('admin/stocks/edit/{id}', [MedicinesController::class, 'update_stock']);
        Route::get('admin/stocks/delete/{id}', [MedicinesController::class, 'delete_stock']);

        Route::prefix('admin/purchases/')->group(function () {
            Route::get('', [PurchasesController::class, 'purchases']);
            Route::get('add', [PurchasesController::class, 'addPurchase']);
            Route::post('add', [PurchasesController::class, 'storePurchase']);
            Route::get('show/{id}', [PurchasesController::class, 'showPurchase']);
            Route::get('edit/{id}', [PurchasesController::class, 'editPurchase']);
            Route::post('edit/{id}', [PurchasesController::class, 'updatePurchase']);
            Route::get('delete/{id}', [PurchasesController::class, 'deletePurchase']);
            Route::get('restore/{id}', [PurchasesController::class, 'restorePurchase']);
        });

        // System Settings (Admin Only)
        Route::get('admin/settings', [AdministrationController::class, 'settings']);
        Route::post('admin/settings', [AdministrationController::class, 'updateSettings']);

        // Activity Logs (Admin Only)
        Route::get('admin/activity-logs', [AdministrationController::class, 'logs']);



        // Inventory Adjustments (Admin Only)
        Route::get('admin/inventory/adjust', [InventoryController::class, 'showAdjustment']);
        Route::post('admin/inventory/adjust', [InventoryController::class, 'submitAdjustment']);
        Route::get('admin/inventory/batches', [InventoryController::class, 'getBatches']);

        // Reports, Analytics & Business Intelligence (Admin Only)
        Route::prefix('admin/reports')->group(function () {
            Route::get('dashboard', [ReportsController::class, 'dashboard']);
            Route::get('sales', [ReportsController::class, 'salesReport']);
            Route::get('sales/excel', [ReportsController::class, 'salesExcel']);
            Route::get('purchases', [ReportsController::class, 'purchaseReport']);
            Route::get('purchases/excel', [ReportsController::class, 'purchaseExcel']);
            Route::get('inventory', [ReportsController::class, 'inventoryReport']);
            Route::get('inventory/excel', [ReportsController::class, 'inventoryExcel']);
            Route::get('customers', [ReportsController::class, 'customerReport']);
            Route::get('suppliers', [ReportsController::class, 'supplierReport']);
            Route::get('medicines', [ReportsController::class, 'medicinePerformance']);
            Route::get('profit', [ReportsController::class, 'profitAnalysis']);
            Route::get('financial', [ReportsController::class, 'financialSummary']);
        });
    });
});
