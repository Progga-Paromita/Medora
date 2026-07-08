<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\MedicinesModel;
use App\Models\StockModel;
use App\Models\StockAdjustmentsModel;
use App\Models\SuppliersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display the inventory monitoring dashboard.
     */
    public function dashboard(): View
    {
        $data['header_title'] = 'Inventory Dashboard';

        // 1. KPIs
        $data['totalMedicines'] = MedicinesModel::where('is_deleted', 0)->count();
        
        $data['totalStockUnits'] = StockModel::where('is_deleted', 0)->sum('quantity');

        $data['lowStockCount'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->where('quantity', '<', 20)
            ->count();

        // Medicines with 0 total active quantity
        $data['outOfStockCount'] = MedicinesModel::where('medicines.is_deleted', 0)
            ->leftJoin('stock', function($join) {
                $join->on('medicines.id', '=', 'stock.medicine_id')
                     ->where('stock.is_deleted', '=', 0);
            })
            ->select('medicines.id', DB::raw('SUM(COALESCE(stock.quantity, 0)) as total_qty'))
            ->groupBy('medicines.id')
            ->having('total_qty', '=', 0)
            ->get()
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

        $data['inventoryValuation'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '>', 0)
            ->select(DB::raw('SUM(quantity * rate) as total_val'))
            ->first()->total_val ?? 0.00;

        // 2. Recent Adjustments
        $data['recentAdjustments'] = StockAdjustmentsModel::with('getMedicine', 'getStock', 'getUser')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.inventory.dashboard', $data);
    }

    /**
     * Display a paginated listing of all stock batches.
     */
    public function listStock(Request $request): View
    {
        $data['header_title'] = 'Stock Registry';
        $data['suppliers'] = SuppliersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $query = StockModel::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.generic_name', 'medicines.packaging')
            ->where('stock.is_deleted', '=', 0);

        // Search: Medicine Name, Generic, Batch
        if (!empty($request->get('search'))) {
            $search = trim($request->get('search'));
            $query->where(function($q) use($search) {
                $q->where('medicines.name', 'like', '%' . $search . '%')
                  ->orWhere('medicines.generic_name', 'like', '%' . $search . '%')
                  ->orWhere('stock.batch_id', 'like', '%' . $search . '%');
            });
        }

        // Filter: Supplier
        if (!empty($request->get('supplier_id'))) {
            $query->where('medicines.supplier_id', '=', $request->get('supplier_id'));
        }

        // Filter: Expiry status
        if (!empty($request->get('expiry_status'))) {
            $status = $request->get('expiry_status');
            if ($status === 'expired') {
                $query->whereDate('stock.expiry_date', '<', date('Y-m-d'));
            } elseif ($status === 'near_expiry') {
                $query->whereDate('stock.expiry_date', '>=', date('Y-m-d'))
                      ->whereDate('stock.expiry_date', '<=', date('Y-m-d', strtotime('+30 days')));
            } elseif ($status === 'active') {
                $query->whereDate('stock.expiry_date', '>=', date('Y-m-d'));
            }
        }

        // Filter: Stock status
        if (!empty($request->get('stock_status'))) {
            $stockStatus = $request->get('stock_status');
            if ($stockStatus === 'low_stock') {
                $query->where('stock.quantity', '>', 0)
                      ->where('stock.quantity', '<', 20);
            } elseif ($stockStatus === 'out_of_stock') {
                $query->where('stock.quantity', '=', 0);
            } elseif ($stockStatus === 'in_stock') {
                $query->where('stock.quantity', '>=', 20);
            }
        }

        // Sorting
        $sortBy = 'stock.id';
        $sortOrder = 'desc';

        if (!empty($request->get('sort_by'))) {
            $allowedSorts = [
                'medicine' => 'medicines.name',
                'batch' => 'stock.batch_id',
                'quantity' => 'stock.quantity',
                'expiry' => 'stock.expiry_date',
                'mrp' => 'stock.mrp',
            ];
            if (array_key_exists($request->get('sort_by'), $allowedSorts)) {
                $sortBy = $allowedSorts[$request->get('sort_by')];
            }
        }

        if (!empty($request->get('sort_order'))) {
            if (in_array(strtolower($request->get('sort_order')), ['asc', 'desc'])) {
                $sortOrder = strtolower($request->get('sort_order'));
            }
        }

        $query->orderBy($sortBy, $sortOrder);
        $data['getRecord'] = $query->paginate(15)->withQueryString();

        return view('admin.inventory.stock', $data);
    }

    /**
     * Display low-stock items.
     */
    public function lowStock(Request $request): View
    {
        $data['header_title'] = 'Low Stock Alert';
        
        $data['getRecord'] = StockModel::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.generic_name', 'medicines.packaging')
            ->where('stock.is_deleted', '=', 0)
            ->where('stock.quantity', '>', 0)
            ->where('stock.quantity', '<', 20)
            ->orderBy('stock.quantity', 'asc')
            ->paginate(15);

        return view('admin.inventory.low_stock', $data);
    }

    /**
     * Display expired items.
     */
    public function expired(Request $request): View
    {
        $data['header_title'] = 'Expired Batches';

        $data['getRecord'] = StockModel::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.generic_name', 'medicines.packaging')
            ->where('stock.is_deleted', '=', 0)
            ->where('stock.quantity', '>', 0)
            ->whereDate('stock.expiry_date', '<', date('Y-m-d'))
            ->orderBy('stock.expiry_date', 'asc')
            ->paginate(15);

        return view('admin.inventory.expired', $data);
    }

    /**
     * Display near-expiry items.
     */
    public function nearExpiry(Request $request): View
    {
        $data['header_title'] = 'Near Expiry Batches';

        $data['getRecord'] = StockModel::select('stock.*')
            ->join('medicines', 'stock.medicine_id', '=', 'medicines.id')
            ->select('stock.*', 'medicines.name as medicine_name', 'medicines.generic_name', 'medicines.packaging')
            ->where('stock.is_deleted', '=', 0)
            ->where('stock.quantity', '>', 0)
            ->whereDate('stock.expiry_date', '>=', date('Y-m-d'))
            ->whereDate('stock.expiry_date', '<=', date('Y-m-d', strtotime('+30 days')))
            ->orderBy('stock.expiry_date', 'asc')
            ->paginate(15);

        return view('admin.inventory.near_expiry', $data);
    }

    /**
     * Show manual stock adjustment form.
     */
    public function showAdjustment(): View
    {
        $data['header_title'] = 'Manual Stock Adjustment';
        $data['medicines'] = MedicinesModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        return view('admin.inventory.adjust', $data);
    }

    /**
     * Endpoint to fetch batches for a medicine.
     */
    public function getBatches(Request $request): JsonResponse
    {
        $batches = StockModel::where('medicine_id', $request->medicine_id)
            ->where('is_deleted', 0)
            ->orderBy('expiry_date', 'asc')
            ->get(['id', 'batch_id', 'quantity', 'expiry_date', 'mrp']);

        return response()->json($batches);
    }

    /**
     * Submit manual stock adjustment.
     */
    public function submitAdjustment(Request $request): RedirectResponse
    {
        $request->validate([
            'medicine_id'     => 'required|exists:medicines,id',
            'stock_id'        => 'required|exists:stock,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity'        => 'required|integer|min:1',
            'reason'          => 'required|string|max:1000',
        ]);

        $stock = StockModel::findOrFail($request->stock_id);
        $qty = (int)$request->quantity;

        if ($request->adjustment_type === 'decrease' && $stock->quantity < $qty) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Adjustment failed. Stock quantity cannot become negative.');
        }

        DB::transaction(function() use ($request, $stock, $qty) {
            // Log adjustment
            $adj = new StockAdjustmentsModel();
            $adj->medicine_id = $request->medicine_id;
            $adj->stock_id = $request->stock_id;
            $adj->user_id = Auth::user()->id;
            $adj->adjustment_type = $request->adjustment_type;
            $adj->quantity = $qty;
            $adj->reason = trim($request->reason);
            $adj->save();

            // Update stock level
            if ($request->adjustment_type === 'increase') {
                $stock->quantity += $qty;
            } else {
                $stock->quantity -= $qty;
            }
            $stock->save();
        });

        return redirect('admin/inventory/dashboard')->with('success', 'Stock adjusted manually and recorded in audit log.');
    }

    /**
     * Display unified movement ledger.
     */
    public function history(Request $request): View
    {
        $data['header_title'] = 'Stock Movement Ledger';

        $purchases = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('medicines', 'purchase_items.medicine_id', '=', 'medicines.id')
            ->select(
                'purchase_items.created_at as transaction_date',
                'medicines.name as medicine_name',
                DB::raw('"Purchase (Stock In)" as type'),
                'purchase_items.quantity as quantity',
                'purchases.voucher_number as reference',
                DB::raw('"System" as user_name')
            )
            ->where('purchases.is_deleted', '=', 0);

        $sales = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('medicines', 'invoice_items.medicine_id', '=', 'medicines.id')
            ->select(
                'invoice_items.created_at as transaction_date',
                'medicines.name as medicine_name',
                DB::raw('"Sale (Stock Out)" as type'),
                'invoice_items.quantity as quantity',
                'invoices.invoice_number as reference',
                DB::raw('"System" as user_name')
            )
            ->where('invoices.is_deleted', '=', 0);

        $adjustments = DB::table('stock_adjustments')
            ->join('medicines', 'stock_adjustments.medicine_id', '=', 'medicines.id')
            ->join('users', 'stock_adjustments.user_id', '=', 'users.id')
            ->select(
                'stock_adjustments.created_at as transaction_date',
                'medicines.name as medicine_name',
                DB::raw('CASE WHEN stock_adjustments.adjustment_type = "increase" THEN "Manual Increase" ELSE "Manual Decrease" END as type'),
                'stock_adjustments.quantity as quantity',
                'stock_adjustments.reason as reference',
                'users.name as user_name'
            );

        $data['getRecord'] = $purchases->union($sales)->union($adjustments)
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        return view('admin.inventory.history', $data);
    }
}
