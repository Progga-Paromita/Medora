<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoicesModel;
use App\Models\SuppliersModel;
use App\Models\PurchasesModel;

class PurchasesController extends Controller
{
    // Purchase List
    public function purchases()
    {
        $data['header_title'] = 'Purchase List';
        $data['getPurchases'] = PurchasesModel::getRecord();

        return view('admin.purchases.list', $data);
    }

    // Add Purchase Form
    public function addPurchase()
    {
        $data['header_title'] = 'Add Purchase';

        $data['getInvoiceNo'] = InvoicesModel::where('is_deleted', 0)
            ->select('id', 'invoice_number', 'net_total')
            ->get();

        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)
            ->select('id', 'name')
            ->get();

        return view('admin.purchases.add', $data);
    }

    // Store Purchase
    public function storePurchase(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|integer',
            'invoice_id'     => 'required|integer',
            'voucher_number' => 'nullable|string|max:255',
            'purchase_date'  => 'required|date',
            'net_total'      => 'required|numeric',
            'payment_status' => 'required|integer',
        ]);

        $purchase = new PurchasesModel();

        $purchase->supplier_id = trim($request->supplier_id);
        $purchase->invoice_id = trim($request->invoice_id);
        $purchase->voucher_number = trim($request->voucher_number);
        $purchase->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $purchase->net_total = trim($request->net_total);
        $purchase->payment_status = trim($request->payment_status);
        $purchase->is_deleted = 0;

        $purchase->save();

        return redirect('admin/purchases')
            ->with('success', 'Purchase Added Successfully');
    }

    // Edit Purchase
    public function editPurchase($id)
    {
        $data['header_title'] = 'Edit Purchase';

        $data['getRecord'] = PurchasesModel::getSingleRecord($id);

        $data['getInvoiceNo'] = InvoicesModel::where('is_deleted', 0)
            ->select('id', 'invoice_number', 'net_total')
            ->get();

        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)
            ->select('id', 'name')
            ->get();

        return view('admin.purchases.edit', $data);
    }

    // Update Purchase
    public function updatePurchase(Request $request, $id)
    {
        $request->validate([
            'supplier_id'    => 'required|integer',
            'invoice_id'     => 'required|integer',
            'voucher_number' => 'nullable|string|max:255',
            'purchase_date'  => 'required|date',
            'net_total'      => 'required|numeric',
            'payment_status' => 'required|integer',
        ]);

        $purchase = PurchasesModel::getSingleRecord($id);

        if (!$purchase) {
            return redirect('admin/purchases')
                ->with('error', 'Purchase not found.');
        }

        $purchase->supplier_id = trim($request->supplier_id);
        $purchase->invoice_id = trim($request->invoice_id);
        $purchase->voucher_number = trim($request->voucher_number);
        $purchase->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $purchase->net_total = trim($request->net_total);
        $purchase->payment_status = trim($request->payment_status);

        $purchase->save();

        return redirect('admin/purchases')
            ->with('success', 'Purchase Updated Successfully');
    }

    // Delete Purchase
    public function deletePurchase($id)
    {
        $purchase = PurchasesModel::getSingleRecord($id);

        if ($purchase) {
            $purchase->is_deleted = 1;
            $purchase->save();
        }

        return redirect('admin/purchases')
            ->with('success', 'Purchase Deleted Successfully');
    }
}
