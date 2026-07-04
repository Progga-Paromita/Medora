<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomersModel;
use App\Models\InvoicesModel;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $data['header_title'] = 'Customers List';
        $data['customers'] = CustomersModel::getRecord($request);

        // Statistics for customers dashboard
        $data['totalCustomers'] = CustomersModel::where('is_deleted', 0)->count();
        $data['newCustomersThisMonth'] = CustomersModel::where('is_deleted', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.customers.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add New Customer';
        return view('admin.customers.add', $data);
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits_between:7,15|unique:customers,phone',
            'email' => 'nullable|email',
            'address' => 'required|string|max:500',
            'doctor_name' => 'nullable|string|max:100',
            'doctor_address' => 'nullable|string|max:500',
        ]);

        $save = new CustomersModel();
        $save->name = trim($request->name);
        $save->phone = trim($request->phone);
        $save->email = trim($request->email);
        $save->address = trim($request->address);
        $save->doctor_name = trim($request->doctor_name);
        $save->doctor_address = trim($request->doctor_address);
        $save->save();

        return redirect('admin/customers')->with('success', 'Customer added successfully');
    }

    public function show($id)
    {
        $customer = CustomersModel::findOrFail($id);
        
        // Fetch purchase/invoice history for the customer
        $invoices = InvoicesModel::where('customer_id', $customer->id)
            ->where('is_deleted', 0)
            ->with(['getInvoiceItems.getMedicine'])
            ->orderBy('invoice_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
            
        // Calculate statistics
        $totalInvoices = $invoices->count();
        
        // Count total unique medicines purchased
        $uniqueMedicines = [];
        $totalQuantityPurchased = 0;
        $totalSalesValue = 0;
        
        foreach ($invoices as $invoice) {
            $totalSalesValue += $invoice->net_total;
            foreach ($invoice->getInvoiceItems as $item) {
                if ($item->medicine_id) {
                    $uniqueMedicines[$item->medicine_id] = true;
                }
                $totalQuantityPurchased += $item->quantity;
            }
        }
        
        $data['customer'] = $customer;
        $data['invoices'] = $invoices;
        $data['totalInvoices'] = $totalInvoices;
        $data['totalQuantityPurchased'] = $totalQuantityPurchased;
        $data['totalMedicinesPurchased'] = count($uniqueMedicines);
        $data['totalSalesValue'] = $totalSalesValue;
        $data['header_title'] = 'Customer Details';
        
        return view('admin.customers.show', $data);
    }

    public function edit($id)
    {
        $data['customer'] = CustomersModel::findOrFail($id);
        $data['header_title'] = 'Edit Customer';
        return view('admin.customers.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $customer = CustomersModel::findOrFail($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits_between:7,15|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email',
            'address' => 'required|string|max:500',
            'doctor_name' => 'nullable|string|max:100',
            'doctor_address' => 'nullable|string|max:500',
        ]);

        $customer->name = trim($request->name);
        $customer->phone = trim($request->phone);
        $customer->email = trim($request->email);
        $customer->address = trim($request->address);
        $customer->doctor_name = trim($request->doctor_name);
        $customer->doctor_address = trim($request->doctor_address);
        $customer->save();

        return redirect('admin/customers')->with('success', 'Customer updated successfully');
    }

    public function delete($id)
    {
        $customer = CustomersModel::findOrFail($id);
        $customer->is_deleted = 1;
        $customer->save();

        return redirect('admin/customers')->with('success', 'Customer deleted successfully');
    }

    public function restore($id)
    {
        $customer = CustomersModel::findOrFail($id);
        $customer->is_deleted = 0;
        $customer->save();

        return redirect('admin/customers')->with('success', 'Customer restored successfully');
    }
}