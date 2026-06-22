<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\InvoicesModel;
use App\Models\CustomersModel;
use App\Models\SuppliersModel;
use App\Models\PurchaseModel;

class PurchasesController extends Controller
{
    public function purchases()
    {
        $data['header_title'] = 'Purchase List';
        return view('admin.purchases.list', $data);
    }

    public function addPurchase()
    {
        $data['header_title'] = 'Add Purchase';

        // Fetch active invoices for selection
        $data['getInvoiceNo'] = InvoicesModel::where('is_deleted', 0)
            ->select('id', 'invoice_number', 'net_total')
            ->get();

        // FIXED: Changed 'supplier_name' to 'name' to resolve the database error
        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)
            ->select('id', 'name')
            ->get();

        return view('admin.purchases.add', $data);
    }

    public function storePurchase(Request $request)
{
    // 1. Validate the incoming request data
    $request->validate([
        'supplier_id'    => 'required|integer',
        'invoice_id'     => 'required|integer',
        'net_total'      => 'required|numeric', // <-- Added validation for total number
        'voucher_number' => 'nullable|string|max:255',
        'purchase_date'  => 'required|date',
        'payment_status' => 'required|integer',
    ]);

    // 2. Instantiate the model and map the request inputs
    $purchase = new PurchasesModel();

    $purchase->supplier_id    = $request->supplier_id;
    $purchase->invoice_id     = $request->invoice_id;
    $purchase->net_total       = $request->net_total; // <-- Added assignment to save to DB
    $purchase->voucher_number = $request->voucher_number;
    $purchase->purchase_date  = $request->purchase_date;
    $purchase->payment_status = $request->payment_status;
    $purchase->is_deleted     = 0;

    // 3. Save to database
    $purchase->save();

    // 4. Redirect
    return redirect('admin/purchases')->with('success', 'Purchase record added successfully!');
}
}
