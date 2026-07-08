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

        $query = ActivityLogsModel::query();

        // Search Name, Email, Role
        if (!empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        // Date Range
        if (!empty($request->get('start_date'))) {
            $query->whereDate('created_at', '>=', $request->get('start_date'));
        }
        if (!empty($request->get('end_date'))) {
            $query->whereDate('created_at', '<=', $request->get('end_date'));
        }

        $data['getRecord'] = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.admin.logs', $data);
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
