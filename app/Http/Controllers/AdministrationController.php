<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\SettingsModel;
use App\Models\ActivityLogsModel;
use App\Models\StockModel;
use App\Models\MedicinesModel;
use App\Models\PurchasesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdministrationController extends Controller
{
    /**
     * Show System Settings form (Admin Only).
     */
    public function settings(): View
    {
        $data['header_title'] = 'System Settings';

        // Load all config values
        $data['pharmacy_name'] = SettingsModel::getValue('pharmacy_name', 'Medora Pharmacy');
        $data['address'] = SettingsModel::getValue('address', '');
        $data['phone'] = SettingsModel::getValue('phone', '');
        $data['email'] = SettingsModel::getValue('email', '');
        $data['website'] = SettingsModel::getValue('website', '');
        $data['currency'] = SettingsModel::getValue('currency', '$');
        $tableTax = SettingsModel::getValue('tax_percentage', '10');
        $data['tax_percentage'] = $tableTax;
        $data['invoice_prefix'] = SettingsModel::getValue('invoice_prefix', 'INV-');
        $data['purchase_prefix'] = SettingsModel::getValue('purchase_prefix', 'PUR-');
        $data['low_stock_threshold'] = SettingsModel::getValue('low_stock_threshold', '20');
        $data['expiry_alert_days'] = SettingsModel::getValue('expiry_alert_days', '30');
        $data['theme'] = SettingsModel::getValue('theme', 'dark');

        return view('admin.admin.settings', $data);
    }

    /**
     * Update System Settings (Admin Only).
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'pharmacy_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'invoice_prefix' => 'required|string|max:20',
            'purchase_prefix' => 'required|string|max:20',
            'low_stock_threshold' => 'required|integer|min:1',
            'expiry_alert_days' => 'required|integer|min:1',
            'theme' => 'required|string|in:light,dark'
        ]);

        SettingsModel::setValue('pharmacy_name', $request->pharmacy_name);
        SettingsModel::setValue('address', $request->address);
        SettingsModel::setValue('phone', $request->phone);
        SettingsModel::setValue('email', $request->email);
        SettingsModel::setValue('website', $request->website);
        SettingsModel::setValue('currency', $request->currency);
        SettingsModel::setValue('tax_percentage', $request->tax_percentage);
        SettingsModel::setValue('invoice_prefix', $request->invoice_prefix);
        SettingsModel::setValue('purchase_prefix', $request->purchase_prefix);
        SettingsModel::setValue('low_stock_threshold', $request->low_stock_threshold);
        SettingsModel::setValue('expiry_alert_days', $request->expiry_alert_days);
        SettingsModel::setValue('theme', $request->theme);

        ActivityLogsModel::log('System settings updated');

        return redirect()->back()->with('success', 'System settings saved successfully.');
    }

    /**
     * Show Activity Logs list (Admin Only).
     */
    public function logs(Request $request): View
    {
        $data['header_title'] = 'Activity & Audit Logs';

        $query = ActivityLogsModel::select('activity_logs.*')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name', 'users.email as user_email');

        // Search IP, Action, Username
        if (!empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use ($search) {
                $q->where('activity_logs.action', 'like', '%' . $search . '%')
                  ->orWhere('activity_logs.ip_address', 'like', '%' . $search . '%')
                  ->orWhere('users.name', 'like', '%' . $search . '%');
            });
        }

        // Date Range
        if (!empty($request->get('start_date'))) {
            $query->whereDate('activity_logs.created_at', '>=', $request->get('start_date'));
        }
        if (!empty($request->get('end_date'))) {
            $query->whereDate('activity_logs.created_at', '<=', $request->get('end_date'));
        }

        $data['getRecord'] = $query->orderBy('activity_logs.created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.admin.logs', $data);
    }

    /**
     * Show Backup page (Admin Only).
     */
    public function backup(): View
    {
        $data['header_title'] = 'Database Backup & Restore';

        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0777, true, true);
        }

        $files = File::files($backupDir);
        $backups = [];
        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'date' => date('Y-m-d H:i:s', $file->getMTime())
                ];
            }
        }

        // Sort backups by date desc
        usort($backups, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        $data['backups'] = $backups;

        return view('admin.admin.backup', $data);
    }

    /**
     * Run Backup creation (Admin Only).
     */
    public function runBackup(): RedirectResponse
    {
        ini_set('memory_limit', '256M');

        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0777, true, true);
        }

        try {
            $tables = [];
            if (DB::getDriverName() === 'sqlite') {
                $rawTables = DB::select('SELECT name FROM sqlite_master WHERE type="table" AND name NOT LIKE "sqlite_%"');
                foreach ($rawTables as $row) {
                    $tables[] = $row->name;
                }
            } else {
                $rawTables = DB::select('SHOW TABLES');
                $dbName = config('database.connections.mysql.database');
                $key = 'Tables_in_' . $dbName;
                foreach ($rawTables as $row) {
                    $tables[] = $row->$key;
                }
            }

            $sql = "-- Medora Pharmacy Database Backup\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            if (DB::getDriverName() === 'sqlite') {
                $sql .= "PRAGMA foreign_keys=OFF;\n\n";
            } else {
                $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            }

            foreach ($tables as $table) {
                if ($table === 'migrations') {
                    continue;
                }

                // Drop table query
                $sql .= "DROP TABLE IF EXISTS " . $table . ";\n";

                // Create table schema query
                if (DB::getDriverName() === 'sqlite') {
                    $createStmt = DB::select('SELECT sql FROM sqlite_master WHERE type="table" AND name=?', [$table]);
                    $sql .= $createStmt[0]->sql . ";\n\n";
                } else {
                    $createStmt = DB::select('SHOW CREATE TABLE ' . $table);
                    $sql .= $createStmt[0]->{'Create Table'} . ";\n\n";
                }

                // Retrieve all rows
                $rows = DB::table($table)->get();
                foreach ($rows as $row) {
                    $arrayRow = (array)$row;
                    $columns = array_keys($arrayRow);
                    $escapedValues = array_map(function($val) {
                        if ($val === null) {
                            return 'NULL';
                        }
                        return "'" . addslashes($val) . "'";
                    }, array_values($arrayRow));

                    $sql .= "INSERT INTO " . $table . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sql .= "\n";
            }

            if (DB::getDriverName() === 'sqlite') {
                $sql .= "PRAGMA foreign_keys=ON;\n";
            } else {
                $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            }

            $fileName = 'backup_' . date('Ymd_His') . '.sql';
            File::put($backupDir . '/' . $fileName, $sql);

            ActivityLogsModel::log('Database backup created: ' . $fileName);

            return redirect()->back()->with('success', 'Database backup file generated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download Backup file (Admin Only).
     */
    public function downloadBackup($fileName)
    {
        $path = storage_path('app/backups/' . basename($fileName));
        if (File::exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'Backup file not found.');
    }

    /**
     * Delete Backup file (Admin Only).
     */
    public function deleteBackup($fileName): RedirectResponse
    {
        $path = storage_path('app/backups/' . basename($fileName));
        if (File::exists($path)) {
            File::delete($path);
            ActivityLogsModel::log('Database backup file deleted: ' . $fileName);
            return redirect()->back()->with('success', 'Backup file deleted.');
        }
        return redirect()->back()->with('error', 'Backup file not found.');
    }

    /**
     * Restore database from backup upload (Admin Only).
     */
    public function restoreBackup(Request $request): RedirectResponse
    {
        $request->validate([
            'backup_file' => 'required|file'
        ]);

        $file = $request->file('backup_file');
        if ($file->getClientOriginalExtension() !== 'sql') {
            return redirect()->back()->with('error', 'Invalid file. Please upload a valid .sql backup file.');
        }

        $sqlContent = File::get($file->getRealPath());
        $sqlLines = explode("\n", $sqlContent);

        DB::beginTransaction();
        try {
            $tempQuery = '';
            foreach ($sqlLines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#') || str_starts_with($trimmed, '/*')) {
                    continue;
                }

                $tempQuery .= $line . "\n";
                if (str_ends_with(trim($line), ';')) {
                    DB::unprepared($tempQuery);
                    $tempQuery = '';
                }
            }
            DB::commit();

            ActivityLogsModel::log('Database restored successfully from upload file');

            return redirect()->back()->with('success', 'Database restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Real-Time Inventory & Payment Notifications list.
     */
    public function notifications(): View
    {
        $data['header_title'] = 'Notification Center';

        // Load config threshold settings
        $lowThreshold = (int)SettingsModel::getValue('low_stock_threshold', 20);
        $alertDays = (int)SettingsModel::getValue('expiry_alert_days', 30);
        $today = date('Y-m-d');
        $expiryLimit = date('Y-m-d', strtotime('+' . $alertDays . ' days'));

        $notifications = [];

        // 1. Low Stock Alerts
        $lowStocks = StockModel::select('stock.*', 'medicines.name as medicine_name')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->where('stock.quantity', '<', $lowThreshold)
            ->get();

        foreach ($lowStocks as $s) {
            $notifications[] = [
                'type' => 'Inventory',
                'title' => 'Low Stock Alert',
                'message' => "Medicine <strong>{$s->medicine_name}</strong> (Batch: {$s->batch_id}) has only <strong>{$s->quantity}</strong> units remaining (threshold: {$lowThreshold}).",
                'class' => 'bg-warning text-dark',
                'icon' => 'bi-exclamation-triangle'
            ];
        }

        // 2. Out of Stock Alerts
        $outOfStocks = MedicinesModel::where('medicines.is_deleted', 0)
            ->leftJoin('stock', function($join) {
                $join->on('medicines.id', '=', 'stock.medicine_id')
                     ->where('stock.is_deleted', '=', 0);
            })
            ->select('medicines.name', DB::raw('SUM(COALESCE(stock.quantity, 0)) as total_qty'))
            ->groupBy('medicines.id', 'medicines.name')
            ->having('total_qty', '=', 0)
            ->get();

        foreach ($outOfStocks as $m) {
            $notifications[] = [
                'type' => 'Inventory',
                'title' => 'Out of Stock Alert',
                'message' => "Medicine <strong>{$m->name}</strong> is completely out of stock. Please place a purchase order.",
                'class' => 'bg-danger text-white',
                'icon' => 'bi-x-circle'
            ];
        }

        // 3. Expired Medicine Alerts
        $expireds = StockModel::select('stock.*', 'medicines.name as medicine_name')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->whereDate('stock.expiry_date', '<', $today)
            ->get();

        foreach ($expireds as $s) {
            $notifications[] = [
                'type' => 'Inventory',
                'title' => 'Expired Medicine Alert',
                'message' => "Batch <strong>{$s->batch_id}</strong> of <strong>{$s->medicine_name}</strong> expired on {$s->expiry_date}. Sell is blocked.",
                'class' => 'bg-danger text-white',
                'icon' => 'bi-calendar-x'
            ];
        }

        // 4. Near Expiry Alerts
        $nearExpiry = StockModel::select('stock.*', 'medicines.name as medicine_name')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->where('stock.is_deleted', 0)
            ->where('stock.quantity', '>', 0)
            ->whereDate('stock.expiry_date', '>=', $today)
            ->whereDate('stock.expiry_date', '<=', $expiryLimit)
            ->get();

        foreach ($nearExpiry as $s) {
            $notifications[] = [
                'type' => 'Inventory',
                'title' => 'Near Expiry Alert',
                'message' => "Batch <strong>{$s->batch_id}</strong> of <strong>{$s->medicine_name}</strong> expires soon on {$s->expiry_date} (within {$alertDays} days).",
                'class' => 'bg-warning text-dark',
                'icon' => 'bi-clock-history'
            ];
        }

        // 5. Pending Purchase Payments
        $pendingPayments = PurchasesModel::select('purchases.*', 'suppliers.name as supplier_name')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->where('purchases.is_deleted', 0)
            ->where('purchases.payment_status', 1)
            ->get();

        foreach ($pendingPayments as $p) {
            $notifications[] = [
                'type' => 'Purchase',
                'title' => 'Payment Reminder',
                'message' => "Purchase order <strong>{$p->voucher_number}</strong> with supplier <strong>{$p->supplier_name}</strong> of amount <strong>\${$p->net_total}</strong> has payment pending.",
                'class' => 'bg-info text-white',
                'icon' => 'bi-bell-fill'
            ];
        }

        $data['notifications'] = $notifications;

        return view('admin.admin.notifications', $data);
    }

    /**
     * Show Help & Support Resources.
     */
    public function help(): View
    {
        $data['header_title'] = 'Help & Support';
        return view('admin.admin.help', $data);
    }
}
