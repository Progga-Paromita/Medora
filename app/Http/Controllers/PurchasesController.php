<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\InvoicesModel;
use App\Models\SuppliersModel;
use App\Models\PurchasesModel;
use App\Models\PurchaseItemsModel;
use App\Models\MedicinesModel;
use App\Models\StockModel;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller
{
    // Purchase List with Filters, Pagination, and Dashboard Statistics
    public function purchases(Request $request): View
    {
        $data['header_title'] = 'Purchase List';
        $data['getPurchases'] = PurchasesModel::getRecord($request);

        // Prepopulate filter dropdowns
        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();

        // Calculate statistics
        $data['totalPurchases'] = PurchasesModel::where('is_deleted', 0)->count();
        
        $data['todayPurchases'] = PurchasesModel::where('is_deleted', 0)
            ->whereDate('purchase_date', today())
            ->count();

        $data['pendingPayments'] = PurchasesModel::where('is_deleted', 0)
            ->where('payment_status', 1)
            ->count();

        $data['acceptedPurchases'] = PurchasesModel::where('is_deleted', 0)
            ->where('payment_status', 2)
            ->count();

        // Current Stock Value: Sum of quantity * purchase_rate in stock
        $data['currentStockValue'] = StockModel::where('is_deleted', 0)
            ->selectRaw('SUM(quantity * rate) as val')
            ->first()->val ?? 0.00;

        // Low stock count (qty < 10)
        $data['lowStockMedicines'] = StockModel::where('is_deleted', 0)
            ->where('quantity', '<', 10)
            ->count();

        // Expiring stock (within 90 days)
        $data['expiringMedicines'] = StockModel::where('is_deleted', 0)
            ->where('expiry_date', '>=', date('Y-m-d'))
            ->where('expiry_date', '<=', date('Y-m-d', strtotime('+90 days')))
            ->count();

        return view('admin.purchases.list', $data);
    }

    // Add Purchase Form
    public function addPurchase(): View
    {
        $data['header_title'] = 'Add Purchase';

        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        $data['getMedicines'] = MedicinesModel::where('is_deleted', 0)
            ->select('id', 'name', 'generic_name', 'packaging')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.purchases.add', $data);
    }

    // Store Purchase with Database Transactions
    public function storePurchase(Request $request): RedirectResponse
    {
        // Enforce Validation rules
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'voucher_number' => 'required|string|max:100|unique:purchases,voucher_number',
            'purchase_date'  => 'required|date',
            'payment_status' => 'required|in:1,2,3',
            'medicines'      => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.batch_id'    => 'required|string|max:50',
            'medicines.*.expiry_date' => 'required|date|after:today',
            'medicines.*.quantity'    => 'required|integer|min:1',
            'medicines.*.purchase_rate' => 'required|numeric|min:0.01',
            'medicines.*.mrp'         => 'required|numeric|min:0.01|gte:medicines.*.purchase_rate',
        ], [
            'voucher_number.unique' => 'The purchase voucher number has already been used.',
            'medicines.required' => 'At least one medicine row must be added.',
            'medicines.*.expiry_date.after' => 'The expiry date must be a future date.',
            'medicines.*.mrp.gte' => 'The MRP (selling price) must be greater than or equal to the purchase rate.',
        ]);

        DB::transaction(function () use ($request) {
            $purchase = new PurchasesModel();
            $purchase->supplier_id = $request->supplier_id;
            $purchase->voucher_number = trim($request->voucher_number);
            $purchase->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $purchase->payment_status = $request->payment_status;
            $purchase->is_deleted = 0;
            $purchase->net_total = 0.00; // will sum up below
            $purchase->save();

            $netTotal = 0.00;

            foreach ($request->medicines as $item) {
                $subtotal = (int)$item['quantity'] * (float)$item['purchase_rate'];
                $netTotal += $subtotal;

                // 1. Create Purchase Item
                $purchaseItem = new PurchaseItemsModel();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->medicine_id = $item['medicine_id'];
                $purchaseItem->quantity = $item['quantity'];
                $purchaseItem->purchase_rate = $item['purchase_rate'];
                $purchaseItem->subtotal = $subtotal;
                $purchaseItem->save();

                // 2. Add / Update Stock
                $stock = StockModel::where('medicine_id', $item['medicine_id'])
                    ->where('batch_id', trim($item['batch_id']))
                    ->where('is_deleted', 0)
                    ->first();

                if ($stock) {
                    $stock->quantity += (int)$item['quantity'];
                    $stock->rate = $item['purchase_rate'];
                    $stock->mrp = $item['mrp'];
                    $stock->expiry_date = date('Y-m-d', strtotime($item['expiry_date']));
                    $stock->save();
                } else {
                    $stock = new StockModel();
                    $stock->medicine_id = $item['medicine_id'];
                    $stock->batch_id = trim($item['batch_id']);
                    $stock->expiry_date = date('Y-m-d', strtotime($item['expiry_date']));
                    $stock->quantity = $item['quantity'];
                    $stock->rate = $item['purchase_rate'];
                    $stock->mrp = $item['mrp'];
                    $stock->is_deleted = 0;
                    $stock->save();
                }
            }

            // Update net total in header
            $purchase->net_total = $netTotal;
            $purchase->save();
        });

        return redirect('admin/purchases')->with('success', 'Purchase recorded and stock levels updated successfully.');
    }

    // View Purchase Details
    public function showPurchase($id): View
    {
        $data['purchase'] = PurchasesModel::findOrFail($id);
        $data['purchaseItems'] = PurchaseItemsModel::where('purchase_id', $id)
            ->with('getMedicine')
            ->get();
        $data['header_title'] = 'Purchase Details';

        return view('admin.purchases.show', $data);
    }

    // Edit Purchase Form
    public function editPurchase($id): View
    {
        $purchase = PurchasesModel::findOrFail($id);
        $data['getRecord'] = $purchase;

        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        $data['getMedicines'] = MedicinesModel::where('is_deleted', 0)
            ->select('id', 'name', 'generic_name', 'packaging')
            ->orderBy('name', 'asc')
            ->get();

        // Get purchase items with stock lookup details so we can prepopulate current batch expiry/mrp
        $purchaseItems = PurchaseItemsModel::where('purchase_id', $id)->get();
        
        $itemsWithBatch = [];
        foreach ($purchaseItems as $item) {
            // Find batch info in stock to get expiry and mrp
            // Since a purchase item could map to a stock entry, look up by medicine_id
            // To be accurate, we can find the matching stock entry for the medicine & date or search
            $stockMatch = StockModel::where('medicine_id', $item->medicine_id)
                ->where('rate', $item->purchase_rate)
                ->where('is_deleted', 0)
                ->first();

            $item->batch_id = $stockMatch->batch_id ?? 'B-NEW';
            $item->expiry_date = $stockMatch->expiry_date ?? date('Y-m-d', strtotime('+1 year'));
            $item->mrp = $stockMatch->mrp ?? ($item->purchase_rate * 1.25);
            $itemsWithBatch[] = $item;
        }

        $data['purchaseItems'] = $itemsWithBatch;
        $data['header_title'] = 'Edit Purchase';

        return view('admin.purchases.edit', $data);
    }

    // Update Purchase with Stock Recalculations
    public function updatePurchase(Request $request, $id): RedirectResponse
    {
        $purchase = PurchasesModel::findOrFail($id);

        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'voucher_number' => 'required|string|max:100|unique:purchases,voucher_number,' . $id,
            'purchase_date'  => 'required|date',
            'payment_status' => 'required|in:1,2,3',
            'medicines'      => 'required|array|min:1',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.batch_id'    => 'required|string|max:50',
            'medicines.*.expiry_date' => 'required|date|after:today',
            'medicines.*.quantity'    => 'required|integer|min:1',
            'medicines.*.purchase_rate' => 'required|numeric|min:0.01',
            'medicines.*.mrp'         => 'required|numeric|min:0.01|gte:medicines.*.purchase_rate',
        ], [
            'voucher_number.unique' => 'The purchase voucher number has already been used.',
            'medicines.required' => 'At least one medicine row must be added.',
            'medicines.*.expiry_date.after' => 'The expiry date must be a future date.',
            'medicines.*.mrp.gte' => 'The MRP (selling price) must be greater than or equal to the purchase rate.',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            // 1. Revert Stock quantities for existing purchase items
            $oldItems = PurchaseItemsModel::where('purchase_id', $purchase->id)->get();
            foreach ($oldItems as $oldItem) {
                // Find stock by looking up medicine and the old rate (or match the batch/mrp if stored)
                // To trace correctly, let's lookup by medicine_id.
                // We'll search for active stock records matching medicine_id
                $stock = StockModel::where('medicine_id', $oldItem->medicine_id)
                    ->where('rate', $oldItem->purchase_rate)
                    ->where('is_deleted', 0)
                    ->first();

                if ($stock) {
                    $stock->quantity -= $oldItem->quantity;
                    if ($stock->quantity < 0) {
                        $stock->quantity = 0;
                    }
                    $stock->save();
                }
            }

            // 2. Delete old items
            PurchaseItemsModel::where('purchase_id', $purchase->id)->delete();

            // 3. Save new items and increase stock
            $netTotal = 0.00;
            foreach ($request->medicines as $item) {
                $subtotal = (int)$item['quantity'] * (float)$item['purchase_rate'];
                $netTotal += $subtotal;

                $purchaseItem = new PurchaseItemsModel();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->medicine_id = $item['medicine_id'];
                $purchaseItem->quantity = $item['quantity'];
                $purchaseItem->purchase_rate = $item['purchase_rate'];
                $purchaseItem->subtotal = $subtotal;
                $purchaseItem->save();

                // Add to stock
                $stock = StockModel::where('medicine_id', $item['medicine_id'])
                    ->where('batch_id', trim($item['batch_id']))
                    ->where('is_deleted', 0)
                    ->first();

                if ($stock) {
                    $stock->quantity += (int)$item['quantity'];
                    $stock->rate = $item['purchase_rate'];
                    $stock->mrp = $item['mrp'];
                    $stock->expiry_date = date('Y-m-d', strtotime($item['expiry_date']));
                    $stock->save();
                } else {
                    $stock = new StockModel();
                    $stock->medicine_id = $item['medicine_id'];
                    $stock->batch_id = trim($item['batch_id']);
                    $stock->expiry_date = date('Y-m-d', strtotime($item['expiry_date']));
                    $stock->quantity = $item['quantity'];
                    $stock->rate = $item['purchase_rate'];
                    $stock->mrp = $item['mrp'];
                    $stock->is_deleted = 0;
                    $stock->save();
                }
            }

            // 4. Update purchase header
            $purchase->supplier_id = $request->supplier_id;
            $purchase->voucher_number = trim($request->voucher_number);
            $purchase->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
            $purchase->payment_status = $request->payment_status;
            $purchase->net_total = $netTotal;
            $purchase->save();
        });

        return redirect('admin/purchases')->with('success', 'Purchase updated and inventory levels recalculated.');
    }

    // Soft Delete Purchase and Revert Stock levels
    public function deletePurchase($id): RedirectResponse
    {
        $purchase = PurchasesModel::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            // Revert stock before deleting
            $oldItems = PurchaseItemsModel::where('purchase_id', $purchase->id)->get();
            foreach ($oldItems as $oldItem) {
                $stock = StockModel::where('medicine_id', $oldItem->medicine_id)
                    ->where('rate', $oldItem->purchase_rate)
                    ->where('is_deleted', 0)
                    ->first();

                if ($stock) {
                    $stock->quantity -= $oldItem->quantity;
                    if ($stock->quantity < 0) {
                        $stock->quantity = 0;
                    }
                    $stock->save();
                }
            }

            // Soft delete purchase header
            $purchase->is_deleted = 1;
            $purchase->save();
        });

        return redirect('admin/purchases')->with('success', 'Purchase softly deleted and stock inventory reverted.');
    }

    // Restore softly deleted purchase and re-increase stock levels
    public function restorePurchase($id): RedirectResponse
    {
        $purchase = PurchasesModel::findOrFail($id);

        DB::transaction(function () use ($purchase) {
            // Re-apply purchase item quantities to stock
            $items = PurchaseItemsModel::where('purchase_id', $purchase->id)->get();
            foreach ($items as $item) {
                // Find stock by medicine (or find default batch)
                // Since batch ID was not saved directly in purchase_items, find stock match
                $stock = StockModel::where('medicine_id', $item->medicine_id)
                    ->where('rate', $item->purchase_rate)
                    ->where('is_deleted', 0)
                    ->first();

                if ($stock) {
                    $stock->quantity += $item->quantity;
                    $stock->save();
                } else {
                    // Recreate stock record if deleted
                    $stock = new StockModel();
                    $stock->medicine_id = $item->medicine_id;
                    $stock->batch_id = 'B-RESTORED';
                    $stock->expiry_date = date('Y-m-d', strtotime('+1 year'));
                    $stock->quantity = $item->quantity;
                    $stock->rate = $item->purchase_rate;
                    $stock->mrp = $item->purchase_rate * 1.25;
                    $stock->is_deleted = 0;
                    $stock->save();
                }
            }

            $purchase->is_deleted = 0;
            $purchase->save();
        });

        return redirect('admin/purchases?status=deleted')->with('success', 'Purchase restored and stock levels adjusted.');
    }
}
