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
use App\Models\User;
use App\Models\SettingsModel;
use App\Models\ActivityLogsModel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display Dashboard
     */
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';

        // 1. Core KPIs
        $data['totalMedicines'] = MedicinesModel::where('is_deleted', 0)->count();
        $data['totalSuppliers'] = SuppliersModel::where('is_deleted', 0)->count();
        $data['totalCustomers'] = CustomersModel::where('is_deleted', 0)->count();
        $data['totalUsers'] = User::where('is_deleted', 0)->count();
        $data['totalPurchases'] = PurchasesModel::where('is_deleted', 0)->count();
        $data['totalSales'] = InvoicesModel::where('is_deleted', 0)->count();
        
        $totalRevenue = InvoicesModel::where('is_deleted', 0)->sum('net_total');
        $data['totalRevenue'] = $totalRevenue;

        // Profit
        $data['totalProfit'] = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->leftJoin('stock', 'invoice_items.stock_id', '=', 'stock.id')
            ->where('invoices.is_deleted', '=', 0)
            ->sum(DB::raw('invoice_items.quantity * (invoice_items.selling_price - COALESCE(stock.rate, 0))'));

        // Inventory valuation
        $data['totalValuation'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->sum(DB::raw('quantity * rate'));

        // Threshold values from settings
        $lowThreshold = (int)SettingsModel::getValue('low_stock_threshold', 20);
        $alertDays = (int)SettingsModel::getValue('expiry_alert_days', 30);
        $today = date('Y-m-d');
        $expiryLimit = date('Y-m-d', strtotime('+' . $alertDays . ' days'));

        $data['lowStock'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->where('quantity', '<', $lowThreshold)
            ->count();

        $data['expiredMedicines'] = StockModel::where('is_deleted', 0)
            ->whereDate('expiry_date', '<', $today)
            ->where('quantity', '>', 0)
            ->count();

        $data['nearExpiry'] = StockModel::where('is_deleted', 0)
            ->whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $expiryLimit)
            ->where('quantity', '>', 0)
            ->count();

        // 2. Chart.js Datasets (Sales vs Purchases for current year)
        $salesMonths = array_fill(1, 12, 0);
        $purchaseMonths = array_fill(1, 12, 0);

        if (DB::getDriverName() === 'sqlite') {
            $rawSales = DB::table('invoices')
                ->select(DB::raw('strftime("%m", invoice_date) as month'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->where(DB::raw('strftime("%Y", invoice_date)'), '=', date('Y'))
                ->groupBy('month')
                ->get();

            $rawPurchases = DB::table('purchases')
                ->select(DB::raw('strftime("%m", purchase_date) as month'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->where(DB::raw('strftime("%Y", purchase_date)'), '=', date('Y'))
                ->groupBy('month')
                ->get();
        } else {
            $rawSales = DB::table('invoices')
                ->select(DB::raw('MONTH(invoice_date) as month'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->whereYear('invoice_date', date('Y'))
                ->groupBy('month')
                ->get();

            $rawPurchases = DB::table('purchases')
                ->select(DB::raw('MONTH(purchase_date) as month'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->whereYear('purchase_date', date('Y'))
                ->groupBy('month')
                ->get();
        }

        foreach ($rawSales as $row) {
            $monthNum = (int)$row->month;
            if ($monthNum >= 1 && $monthNum <= 12) {
                $salesMonths[$monthNum] = (float)$row->total;
            }
        }

        foreach ($rawPurchases as $row) {
            $monthNum = (int)$row->month;
            if ($monthNum >= 1 && $monthNum <= 12) {
                $purchaseMonths[$monthNum] = (float)$row->total;
            }
        }

        $data['monthlySalesJSON'] = json_encode(array_values($salesMonths));
        $data['monthlyPurchasesJSON'] = json_encode(array_values($purchaseMonths));

        // Top 5 selling medicines
        $topMedicines = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('medicines', 'invoice_items.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', DB::raw('SUM(invoice_items.quantity) as total_qty'))
            ->where('invoices.is_deleted', 0)
            ->groupBy('medicines.id', 'medicines.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        $data['topMedNames'] = json_encode($topMedicines->pluck('name'));
        $data['topMedQty'] = json_encode($topMedicines->pluck('total_qty')->map(fn($v) => (int)$v));

        // Inventory Distribution (Pie Chart)
        $inventoryDist = DB::table('stock')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', DB::raw('SUM(stock.quantity) as total_qty'))
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->groupBy('medicines.id', 'medicines.name')
            ->limit(8)
            ->get();

        $data['distMedNames'] = json_encode($inventoryDist->pluck('name'));
        $data['distMedQty'] = json_encode($inventoryDist->pluck('total_qty')->map(fn($v) => (int)$v));

        // 3. Recent Activities Feed
        $data['recentActivities'] = ActivityLogsModel::orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // 4. Notifications center (Recent 4 real-time alerts)
        $alerts = [];
        // Low Stock alert
        $lowStockBatch = StockModel::select('stock.*', 'medicines.name as medicine_name')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->where('stock.quantity', '<', $lowThreshold)
            ->first();
        if ($lowStockBatch) {
            $alerts[] = [
                'type' => 'Low Stock Alert',
                'message' => "{$lowStockBatch->medicine_name} is running low ({$lowStockBatch->quantity} left)",
                'class' => 'text-warning'
            ];
        }

        // Expired alert
        $expiredBatch = StockModel::select('stock.*', 'medicines.name as medicine_name')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->whereDate('stock.expiry_date', '<', $today)
            ->first();
        if ($expiredBatch) {
            $alerts[] = [
                'type' => 'Expired Medicine',
                'message' => "{$expiredBatch->medicine_name} (Batch: {$expiredBatch->batch_id}) has expired",
                'class' => 'text-danger'
            ];
        }

        // Near Expiry Alert
        $nearExpiryBatch = StockModel::select('stock.*', 'medicines.name as medicine_name')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->whereDate('stock.expiry_date', '>=', $today)
            ->whereDate('stock.expiry_date', '<=', $expiryLimit)
            ->first();
        if ($nearExpiryBatch) {
            $alerts[] = [
                'type' => 'Near Expiry Alert',
                'message' => "{$nearExpiryBatch->medicine_name} expires within {$alertDays} days",
                'class' => 'text-warning'
            ];
        }

        // Pending payments
        $pendingOrder = PurchasesModel::where('is_deleted', 0)->where('payment_status', 1)->first();
        if ($pendingOrder) {
            $alerts[] = [
                'type' => 'Payment Pending',
                'message' => "Voucher {$pendingOrder->voucher_number} payment is pending",
                'class' => 'text-info'
            ];
        }

        $data['alerts'] = $alerts;

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

        ActivityLogsModel::log('User profile updated');

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
