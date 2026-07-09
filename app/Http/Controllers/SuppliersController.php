<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\SuppliersModel;
use App\Models\PurchasesModel;

class SuppliersController extends Controller
{
    public function list(Request $request): View
    {
        $data['getRecord'] = SuppliersModel::getRecord($request);
        $data['header_title'] = 'Suppliers List';

        // Statistics for suppliers dashboard
        $data['totalSuppliers'] = SuppliersModel::where('is_deleted', 0)->count();
        $data['newSuppliersThisMonth'] = SuppliersModel::where('is_deleted', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.suppliers.list', $data);
    }

    public function create_suppliers(): View
    {
        $data['header_title'] = 'Add New Supplier';
        return view('admin.suppliers.add', $data);
    }

    public function insert_suppliers(Request $request): RedirectResponse
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits_between:7,15|unique:suppliers,phone',
            'email' => 'nullable|email',
            'address' => 'required|string|max:500',
        ]);

        $save = new SuppliersModel();
        $save->name = trim($request->name);
        $save->phone = trim($request->phone);
        $save->email = trim($request->email);
        $save->address = trim($request->address);
        $save->save();

        return redirect('admin/suppliers')
            ->with('success', 'Supplier added successfully');
    }

    public function show($id): View
    {
        $supplier = SuppliersModel::findOrFail($id);
        
        // Fetch supply history (purchases with items and medicines)
        $purchases = PurchasesModel::where('supplier_id', $supplier->id)
            ->where('is_deleted', 0)
            ->with(['getPurchaseItems.getMedicine'])
            ->orderBy('purchase_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
            
        // Calculate statistics
        $totalPurchases = $purchases->count();
        
        $totalBillAmount = 0;
        $paidAmount = 0;
        $pendingAmount = 0;
        $totalQuantitySupplied = 0;
        
        foreach ($purchases as $purchase) {
            // Only count if not cancelled (status != 3)
            if ($purchase->payment_status != 3) {
                $totalBillAmount += $purchase->net_total;
                if ($purchase->payment_status == 2) {
                    $paidAmount += $purchase->net_total;
                } elseif ($purchase->payment_status == 1) {
                    $pendingAmount += $purchase->net_total;
                }
            }
            foreach ($purchase->getPurchaseItems as $item) {
                $totalQuantitySupplied += $item->quantity;
            }
        }
        
        $data['supplier'] = $supplier;
        $data['purchases'] = $purchases;
        $data['totalPurchases'] = $totalPurchases;
        $data['totalQuantitySupplied'] = $totalQuantitySupplied;
        $data['totalBillAmount'] = $totalBillAmount;
        $data['paidAmount'] = $paidAmount;
        $data['pendingAmount'] = $pendingAmount;
        $data['header_title'] = 'Supplier Details';
        
        return view('admin.suppliers.show', $data);
    }

    public function edit_suppliers($id): View
    {
        $data['getRecord'] = SuppliersModel::getSingleRecord($id);
        $data['header_title'] = 'Edit Supplier';
        return view('admin.suppliers.edit', $data);
    }

    public function update_suppliers(Request $request, $id): RedirectResponse
    {
        $save = SuppliersModel::getSingleRecord($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits_between:7,15|unique:suppliers,phone,' . $save->id,
            'email' => 'nullable|email',
            'address' => 'required|string|max:500',
        ]);

        $save->name = trim($request->name);
        $save->phone = trim($request->phone);
        $save->email = trim($request->email);
        $save->address = trim($request->address);
        $save->save();

        return redirect('admin/suppliers')
            ->with('success', 'Supplier updated successfully');
    }

    public function delete_suppliers($id): RedirectResponse
    {
        $save = SuppliersModel::getSingleRecord($id);
        $save->is_deleted = 1;
        $save->save();

        return redirect('admin/suppliers')
            ->with('success', 'Supplier deleted successfully');
    }

    public function restore($id): RedirectResponse
    {
        $supplier = SuppliersModel::findOrFail($id);
        $supplier->is_deleted = 0;
        $supplier->save();

        return redirect('admin/suppliers')
            ->with('success', 'Supplier restored successfully');
    }
}