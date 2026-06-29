<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\MedicinesModel;
use App\Models\SuppliersModel;
use App\Models\CustomersModel;
use App\Models\PurchasesModel;
use App\Models\InvoicesModel;
use App\Models\StockModel;

class DashboardController extends Controller
{
    /**
     * Display Dashboard
     */
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';

        // Load 9 statistic counts from database
        $data['totalMedicines'] = MedicinesModel::where('is_deleted', 0)->count();
        $data['totalSuppliers'] = SuppliersModel::where('is_deleted', 0)->count();
        $data['totalCustomers'] = CustomersModel::where('is_deleted', 0)->count();
        $data['totalPurchases'] = PurchasesModel::where('is_deleted', 0)->count();
        $data['totalSales'] = InvoicesModel::where('is_deleted', 0)->count();
        $data['totalRevenue'] = InvoicesModel::where('is_deleted', 0)->sum('net_total');
        
        $data['totalStock'] = StockModel::where('is_deleted', 0)->sum('quantity') ?? 0;
        $data['lowStock'] = StockModel::where('is_deleted', 0)->where('quantity', '<', 10)->count();
        $data['expiredMedicines'] = StockModel::where('is_deleted', 0)->where('expiry_date', '<', date('Y-m-d'))->count();

        return view('admin.dashboard.dashboard', $data);
    }

    /**
     * Display My Account Page
     */
    public function my_account()
    {
        $data['header_title'] = 'My Account';
        $data['getRecord'] = Auth::user();

        return view('admin.dashboard.my_account', $data);
    }

    /**
     * Update My Account
     */
    public function update_account(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/')->with('error', 'Please login first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = trim($request->name);
        $user->last_name = trim($request->last_name);
        $user->email = trim($request->email);
        $user->phone = trim($request->phone);

        // Profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if (!empty($user->profile_image) && file_exists(public_path('uploads/profile/' . $user->profile_image))) {
                @unlink(public_path('uploads/profile/' . $user->profile_image));
            }

            $file = $request->file('profile_image');
            $ext = $file->getClientOriginalExtension();
            $randomStr = Str::random(20);
            $fileName = $randomStr . '.' . $ext;

            $file->move(public_path('uploads/profile'), $fileName);
            $user->profile_image = $fileName;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
