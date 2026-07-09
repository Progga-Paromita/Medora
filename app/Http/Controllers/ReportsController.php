<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use App\Models\InvoicesModel;
use App\Models\InvoiceItemsModel;
use App\Models\PurchasesModel;
use App\Models\CustomersModel;
use App\Models\SuppliersModel;
use App\Models\MedicinesModel;
use App\Models\StockModel;
use App\Models\StockAdjustmentsModel;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Reports & Analytics Business Intelligence Dashboard.
     */
    public function dashboard(): View
    {
        $data['header_title'] = 'Reports Dashboard';

        // 1. KPI Cards
        $totalRevenue = InvoicesModel::where('is_deleted', 0)->sum('net_total');
        $data['totalRevenue'] = $totalRevenue;

        $data['todaySales'] = InvoicesModel::where('is_deleted', 0)
            ->whereDate('invoice_date', today())
            ->sum('net_total');

        $data['monthlySales'] = InvoicesModel::where('is_deleted', 0)
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('net_total');

        $data['totalPurchases'] = PurchasesModel::where('is_deleted', 0)->sum('net_total');

        // Gross Profit = Selling subtotal - Purchase cost of batches sold
        $data['grossProfit'] = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->leftJoin('stock', 'invoice_items.stock_id', '=', 'stock.id')
            ->where('invoices.is_deleted', '=', 0)
            ->sum(DB::raw('invoice_items.quantity * (invoice_items.selling_price - COALESCE(stock.rate, 0))'));

        $data['inventoryValuation'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->select(DB::raw('SUM(quantity * rate) as total_val'))
            ->first()->total_val ?? 0.00;

        $data['totalCustomers'] = CustomersModel::where('is_deleted', 0)->count();
        $data['totalSuppliers'] = SuppliersModel::where('is_deleted', 0)->count();
        $data['totalMedicines'] = MedicinesModel::where('is_deleted', 0)->count();

        $data['lowStockCount'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->where('quantity', '<', 20)
            ->count();

        $data['expiredCount'] = StockModel::where('is_deleted', 0)
            ->whereDate('expiry_date', '<', date('Y-m-d'))
            ->where('quantity', '>', 0)
            ->count();

        $data['nearExpiryCount'] = StockModel::where('is_deleted', 0)
            ->whereDate('expiry_date', '>=', date('Y-m-d'))
            ->whereDate('expiry_date', '<=', date('Y-m-d', strtotime('+30 days')))
            ->where('quantity', '>', 0)
            ->count();

        $daysCount = InvoicesModel::where('is_deleted', 0)->distinct('invoice_date')->count('invoice_date');
        $data['avgDailySales'] = $daysCount > 0 ? ($totalRevenue / $daysCount) : 0.00;
        $data['avgInvoiceValue'] = InvoicesModel::where('is_deleted', 0)->avg('net_total') ?? 0.00;

        // 2. Chart.js Datasets (Monthly sales/purchases for current year)
        $salesMonths = array_fill(1, 12, 0);
        $purchaseMonths = array_fill(1, 12, 0);

        // Fetch monthly invoices and purchases with sqlite compatibility
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

        return view('admin.reports.dashboard', $data);
    }

    /**
     * Sales reports query helpers.
     */
    private function getSalesQuery(Request $request)
    {
        $query = InvoicesModel::select('invoices.*')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select('invoices.*', 'customers.name as customer_name', 'customers.phone as customer_phone')
            ->where('invoices.is_deleted', 0);

        // Date Filter Preset
        if (!empty($request->get('date_preset'))) {
            $preset = $request->get('date_preset');
            $today = date('Y-m-d');

            if ($preset === 'today') {
                $query->whereDate('invoices.invoice_date', '=', $today);
            } elseif ($preset === 'yesterday') {
                $query->whereDate('invoices.invoice_date', '=', date('Y-m-d', strtotime('-1 day')));
            } elseif ($preset === 'this_week') {
                $query->whereDate('invoices.invoice_date', '>=', date('Y-m-d', strtotime('monday this week')));
            } elseif ($preset === 'this_month') {
                $query->whereMonth('invoices.invoice_date', '=', now()->month)
                      ->whereYear('invoices.invoice_date', '=', now()->year);
            } elseif ($preset === 'this_year') {
                $query->whereYear('invoices.invoice_date', '=', date('Y'));
            }
        }

        // Custom Date Range
        if (!empty($request->get('start_date'))) {
            $query->whereDate('invoices.invoice_date', '>=', $request->get('start_date'));
        }
        if (!empty($request->get('end_date'))) {
            $query->whereDate('invoices.invoice_date', '<=', $request->get('end_date'));
        }

        // Filter Customer
        if (!empty($request->get('customer_id'))) {
            $query->where('invoices.customer_id', '=', $request->get('customer_id'));
        }

        // Search Keyword
        if (!empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use ($search) {
                $q->where('invoices.invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('customers.name', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    /**
     * Sales Report Page.
     */
    public function salesReport(Request $request): View
    {
        $data['header_title'] = 'Sales Performance Report';
        $data['customers'] = CustomersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $query = $this->getSalesQuery($request);
        $data['totalInvoices'] = $query->count();
        $data['grossSales'] = $query->sum('total_amount');
        $data['discounts'] = $query->sum('total_discount');
        $data['netRevenue'] = $query->sum('net_total');

        $data['getRecord'] = $query->orderBy('invoices.invoice_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.sales', $data);
    }

    /**
     * Sales Excel export attachment.
     */
    public function salesExcel(Request $request): Response
    {
        $query = $this->getSalesQuery($request);
        $data['getRecord'] = $query->orderBy('invoices.invoice_date', 'desc')->get();
        $data['totalInvoices'] = $query->count();
        $data['netRevenue'] = $query->sum('net_total');

        return response()->view('admin.reports.excel_sales', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="sales_report_' . date('Ymd_His') . '.xls"');
    }

    /**
     * Purchases Query helper.
     */
    private function getPurchasesQuery(Request $request)
    {
        $query = PurchasesModel::select('purchases.*')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select('purchases.*', 'suppliers.name as supplier_name')
            ->where('purchases.is_deleted', 0);

        if (!empty($request->get('start_date'))) {
            $query->whereDate('purchases.purchase_date', '>=', $request->get('start_date'));
        }
        if (!empty($request->get('end_date'))) {
            $query->whereDate('purchases.purchase_date', '<=', $request->get('end_date'));
        }
        if (!empty($request->get('supplier_id'))) {
            $query->where('purchases.supplier_id', '=', $request->get('supplier_id'));
        }
        if ($request->get('payment_status') !== null && $request->get('payment_status') !== '') {
            $query->where('purchases.payment_status', '=', $request->get('payment_status'));
        }

        return $query;
    }

    /**
     * Purchase Report Page.
     */
    public function purchaseReport(Request $request): View
    {
        $data['header_title'] = 'Purchase Statement Report';
        $data['suppliers'] = SuppliersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $query = $this->getPurchasesQuery($request);
        $data['totalPurchasesCount'] = $query->count();
        $data['totalPurchaseVal'] = $query->sum('net_total');

        // Pending payments (status = 1 is Pending)
        $data['pendingPayments'] = $query->clone()->where('payment_status', 1)->sum('net_total');
        $data['completedPayments'] = $query->clone()->where('payment_status', 2)->sum('net_total');

        $data['getRecord'] = $query->orderBy('purchases.purchase_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.purchases', $data);
    }

    /**
     * Purchase Excel sheet export.
     */
    public function purchaseExcel(Request $request): Response
    {
        $query = $this->getPurchasesQuery($request);
        $data['getRecord'] = $query->orderBy('purchases.purchase_date', 'desc')->get();

        return response()->view('admin.reports.excel_purchases', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="purchases_report_' . date('Ymd_His') . '.xls"');
    }

    /**
     * Inventory Report Page.
     */
    public function inventoryReport(Request $request): View
    {
        $data['header_title'] = 'Valued Inventory Statement';
        $data['suppliers'] = SuppliersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $query = StockModel::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.packaging')
            ->where('stock.is_deleted', 0);

        if (!empty($request->get('supplier_id'))) {
            $query->where('medicines.supplier_id', '=', $request->get('supplier_id'));
        }
        if (!empty($request->get('stock_status'))) {
            $status = $request->get('stock_status');
            if ($status === 'low_stock') {
                $query->where('stock.quantity', '>', 0)->where('stock.quantity', '<', 20);
            } elseif ($status === 'expired') {
                $query->whereDate('stock.expiry_date', '<', date('Y-m-d'));
            }
        }

        $data['totalValuation'] = $query->clone()->sum(DB::raw('stock.quantity * stock.rate'));
        $data['totalStockUnits'] = $query->clone()->sum('stock.quantity');
        $data['getRecord'] = $query->orderBy('medicines.name', 'asc')->paginate(15)->withQueryString();

        return view('admin.reports.inventory', $data);
    }

    /**
     * Inventory Excel export.
     */
    public function inventoryExcel(Request $request): Response
    {
        $query = StockModel::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.packaging')
            ->where('stock.is_deleted', 0);

        $data['getRecord'] = $query->orderBy('medicines.name', 'asc')->get();

        return response()->view('admin.reports.excel_inventory', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="inventory_valuation_' . date('Ymd_His') . '.xls"');
    }

    /**
     * Customer Transactions Report.
     */
    public function customerReport(Request $request): View
    {
        $data['header_title'] = 'Customer Purchase Ledger';

        $data['getRecord'] = CustomersModel::where('customers.is_deleted', 0)
            ->leftJoin('invoices', function($join) {
                $join->on('customers.id', '=', 'invoices.customer_id')
                     ->where('invoices.is_deleted', '=', 0);
            })
            ->select(
                'customers.name',
                'customers.phone',
                'customers.email',
                DB::raw('COUNT(invoices.id) as total_purchases'),
                DB::raw('SUM(COALESCE(invoices.net_total, 0)) as total_spending'),
                DB::raw('MAX(invoices.invoice_date) as last_purchase')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone', 'customers.email')
            ->orderBy('total_spending', 'desc')
            ->paginate(15);

        return view('admin.reports.customers', $data);
    }

    /**
     * Supplier Procurement Report.
     */
    public function supplierReport(Request $request): View
    {
        $data['header_title'] = 'Supplier Purchasing Report';

        $data['getRecord'] = SuppliersModel::where('suppliers.is_deleted', 0)
            ->leftJoin('purchases', function($join) {
                $join->on('suppliers.id', '=', 'purchases.supplier_id')
                     ->where('purchases.is_deleted', '=', 0);
            })
            ->select(
                'suppliers.name',
                'suppliers.phone',
                'suppliers.address',
                DB::raw('COUNT(purchases.id) as total_purchases'),
                DB::raw('SUM(COALESCE(purchases.net_total, 0)) as total_amount'),
                DB::raw('MAX(purchases.purchase_date) as last_purchase')
            )
            ->groupBy('suppliers.id', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->orderBy('total_amount', 'desc')
            ->paginate(15);

        return view('admin.reports.suppliers', $data);
    }

    /**
     * Medicine Sales Performance Report.
     */
    public function medicinePerformance(Request $request): View
    {
        $data['header_title'] = 'Medicine Performance Report';

        $query = MedicinesModel::where('medicines.is_deleted', 0)
            ->leftJoin('invoice_items', 'medicines.id', '=', 'invoice_items.medicine_id')
            ->leftJoin('invoices', function($join) {
                $join->on('invoice_items.invoice_id', '=', 'invoices.id')
                     ->where('invoices.is_deleted', '=', 0);
            })
            ->select(
                'medicines.name',
                'medicines.generic_name',
                'medicines.packaging',
                DB::raw('SUM(COALESCE(invoice_items.quantity, 0)) as qty_sold'),
                DB::raw('SUM(COALESCE(invoice_items.subtotal, 0)) as revenue')
            )
            ->groupBy('medicines.id', 'medicines.name', 'medicines.generic_name', 'medicines.packaging');

        // Filter performance category
        $category = $request->get('performance_category');
        if ($category === 'best_selling') {
            $query->orderBy('qty_sold', 'desc');
        } elseif ($category === 'least_selling') {
            $query->having('qty_sold', '>', 0)->orderBy('qty_sold', 'asc');
        } elseif ($category === 'never_sold') {
            $query->having('qty_sold', '=', 0);
        } else {
            $query->orderBy('qty_sold', 'desc');
        }

        $data['getRecord'] = $query->paginate(15)->withQueryString();

        return view('admin.reports.medicines', $data);
    }

    /**
     * profit analysis by month.
     */
    public function profitAnalysis(Request $request): View
    {
        $data['header_title'] = 'Monthly Profit Analysis';

        // Monthly purchase costs, sales revenues, and gross profits with SQLite/MySQL cross compatibility
        if (DB::getDriverName() === 'sqlite') {
            $purchaseCosts = DB::table('purchases')
                ->select(DB::raw('strftime("%Y", purchase_date) as yr'), DB::raw('strftime("%m", purchase_date) as mo'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->groupBy('yr', 'mo')
                ->get();

            $salesRevenues = DB::table('invoices')
                ->select(DB::raw('strftime("%Y", invoice_date) as yr'), DB::raw('strftime("%m", invoice_date) as mo'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->groupBy('yr', 'mo')
                ->get();

            $monthlyProfits = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->leftJoin('stock', 'invoice_items.stock_id', '=', 'stock.id')
                ->select(
                    DB::raw('strftime("%Y", invoices.invoice_date) as yr'),
                    DB::raw('strftime("%m", invoices.invoice_date) as mo'),
                    DB::raw('SUM(invoice_items.quantity * (invoice_items.selling_price - COALESCE(stock.rate, 0))) as profit')
                )
                ->where('invoices.is_deleted', 0)
                ->groupBy('yr', 'mo')
                ->get();
        } else {
            $purchaseCosts = DB::table('purchases')
                ->select(DB::raw('YEAR(purchase_date) as yr'), DB::raw('MONTH(purchase_date) as mo'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->groupBy('yr', 'mo')
                ->get();

            $salesRevenues = DB::table('invoices')
                ->select(DB::raw('YEAR(invoice_date) as yr'), DB::raw('MONTH(invoice_date) as mo'), DB::raw('SUM(net_total) as total'))
                ->where('is_deleted', 0)
                ->groupBy('yr', 'mo')
                ->get();

            $monthlyProfits = DB::table('invoice_items')
                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                ->leftJoin('stock', 'invoice_items.stock_id', '=', 'stock.id')
                ->select(
                    DB::raw('YEAR(invoices.invoice_date) as yr'),
                    DB::raw('MONTH(invoices.invoice_date) as mo'),
                    DB::raw('SUM(invoice_items.quantity * (invoice_items.selling_price - COALESCE(stock.rate, 0))) as profit')
                )
                ->where('invoices.is_deleted', 0)
                ->groupBy('yr', 'mo')
                ->get();
        }

        // Compile combined datasets by year-month keys
        $monthlyRecords = [];

        // Build list of months for current year and previous year
        for ($year = date('Y'); $year >= date('Y') - 1; $year--) {
            for ($month = 12; $month >= 1; $month--) {
                $key = "{$year}-" . sprintf('%02d', $month);
                $monthlyRecords[$key] = [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => date('F', mktime(0, 0, 0, $month, 10)),
                    'purchases' => 0.00,
                    'sales' => 0.00,
                    'profit' => 0.00
                ];
            }
        }

        foreach ($purchaseCosts as $row) {
            $key = "{$row->yr}-" . sprintf('%02d', (int)$row->mo);
            if (isset($monthlyRecords[$key])) {
                $monthlyRecords[$key]['purchases'] = (float)$row->total;
            }
        }

        foreach ($salesRevenues as $row) {
            $key = "{$row->yr}-" . sprintf('%02d', (int)$row->mo);
            if (isset($monthlyRecords[$key])) {
                $monthlyRecords[$key]['sales'] = (float)$row->total;
            }
        }

        foreach ($monthlyProfits as $row) {
            $key = "{$row->yr}-" . sprintf('%02d', (int)$row->mo);
            if (isset($monthlyRecords[$key])) {
                $monthlyRecords[$key]['profit'] = (float)$row->profit;
            }
        }

        // Filter records that have actual sales or purchases (remove completely empty months)
        $filteredRecords = array_filter($monthlyRecords, function($row) {
            return $row['sales'] > 0 || $row['purchases'] > 0 || $row['profit'] > 0;
        });

        // Paginate manually using collection helper
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($filteredRecords);
        $perPage = 12;
        $currentPageItems = $itemCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $data['getRecord'] = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
        $data['getRecord']->setPath($request->url());

        return view('admin.reports.profit', $data);
    }

    /**
     * Financial Report summary sheet.
     */
    public function financialSummary(): View
    {
        $data['header_title'] = 'Overall Financial Statement';

        $data['totalRevenue'] = InvoicesModel::where('is_deleted', 0)->sum('net_total');
        $data['totalPurchases'] = PurchasesModel::where('is_deleted', 0)->sum('net_total');

        $data['grossProfit'] = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->leftJoin('stock', 'invoice_items.stock_id', '=', 'stock.id')
            ->where('invoices.is_deleted', 0)
            ->sum(DB::raw('invoice_items.quantity * (invoice_items.selling_price - COALESCE(stock.rate, 0))'));

        $data['inventoryValuation'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->sum(DB::raw('quantity * rate'));

        return view('admin.reports.financial', $data);
    }
}
