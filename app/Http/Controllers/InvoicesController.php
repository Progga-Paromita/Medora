<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse; // <-- Added this to fix the TypeError
use App\Models\InvoicesModel;
use App\Models\CustomersModel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function list(): View
    {
        $data['getRecord'] = InvoicesModel::getRecord();
        $data['header_title'] = 'Invoices List';

        return view('admin.invoices.list', $data);
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function add(): View
    {
        $data['getCustomer'] = CustomersModel::getRecord();
        $data['header_title'] = 'Add New Invoice';

        return view('admin.invoices.add', $data);
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //get form inputs
        $total_amount = $request->input('total_amount');
        $total_discount = $request->input('total_discount');
        $tax = $request->input('tax');

        //calculate net total
        $net_total = $total_amount - $total_discount;
        $net_total = $net_total + ( $tax/100 * $net_total );

        $save = new InvoicesModel();
        $save->invoice_number=trim($request->invoice_number);
        $save->customer_id = trim($request->customer_id);
        $save->invoice_date = trim($request->invoice_date);
        $save->total_amount = trim($request->total_amount);
        $save->total_discount = trim($request->total_discount);
        $save->tax = trim($request->tax);
        $save->net_total = $net_total;
        $save->save();

        return redirect('admin/invoices')->with('success', 'Invoice added successfully');
    }
    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id): View
    {
        $data['getRecord'] = InvoicesModel::getSingleRecord($id);
        $data['getCustomer'] = CustomersModel::getRecord(); // Fetches customers for your dropdown menu
        $data['header_title'] = 'Edit Invoice';

        return view('admin.invoices.edit', $data);
    }

    /**
     * Update the specified invoice in storage.
     */
  /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    { // <-- ADD THIS OPENING BRACE HERE

        //get form inputs
        $total_amount = $request->input('total_amount');
        $total_discount = $request->input('total_discount', 0);
        $tax = $request->input('tax', 0);

        //calculate net total
        $net_total = $total_amount - $total_discount;
        $net_total = $net_total + ($tax / 100 * $net_total);

        $save = InvoicesModel::getSingleRecord($id);

        if ($save) {
            $save->invoice_number = trim($request->invoice_number);
            $save->customer_id    = trim($request->customer_id);
            $save->invoice_date   = trim($request->invoice_date);
            $save->total_amount   = trim($request->total_amount);
            $save->total_discount = trim($request->total_discount);
            $save->tax            = trim($request->tax);
            $save->net_total      = $net_total;
            $save->save();

            return redirect('admin/invoices')->with('success', 'Invoice updated successfully');
        }

        return redirect('admin/invoices')->with('error', 'Invoice not found');
    } // <-- Ensure this closing brace matches up at the very end

    /**
     * Soft delete the specified invoice.
     */
    public function delete($id): RedirectResponse
    {
        $save = InvoicesModel::getSingleRecord($id);

        if ($save) {
            $save->is_deleted = 1;
            $save->save();

            return redirect('admin/invoices')->with('success', 'Invoice deleted successfully');
        }

        return redirect('admin/invoices')->with('error', 'Invoice not found');
    }
}
