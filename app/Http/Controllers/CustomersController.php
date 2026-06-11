<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomersModel;

class CustomersController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Customers List';
        $data['customers'] = CustomersModel::all();

        return view('admin.customers.list', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add New Customer';

        return view('admin.customers.add', $data);
    }

    public function store(Request $request)
{
    // dd($request->all());

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

    public function edit($id)
    {
        $data['customer'] = CustomersModel::findOrFail($id);
        $data['header_title'] = 'Edit Customer';

        return view('admin.customers.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $customer = CustomersModel::findOrFail($id);

        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->doctor_name = $request->doctor_name;
        $customer->doctor_address = $request->doctor_address;

        $customer->save();

        return redirect('admin/customers')->with('success', 'Customer updated successfully');
    }

    public function delete($id)
    {
        $customer = CustomersModel::findOrFail($id);
        $customer->delete();

        return redirect('admin/customers')->with('success', 'Customer deleted successfully');
    }
}