<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\SuppliersModel;

class SuppliersController extends Controller
{
    public function list(): View
    {
        $data['getRecord'] = SuppliersModel::getRecord();
        $data['header_title'] = 'Suppliers List';

        return view('admin.suppliers.list', $data);
    }

    public function create_suppliers(): View
    {
        $data['header_title'] = 'Add New Supplier';

        return view('admin.suppliers.add', $data);
    }

    public function insert_suppliers(Request $request): RedirectResponse
    {
        $save = new SuppliersModel();

        $save->name = trim($request->name);
        $save->phone = trim($request->phone);
        $save->email = trim($request->email);
        $save->address = trim($request->address);

        $save->save();

        return redirect('admin/suppliers')
            ->with('success', 'Supplier added successfully');
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
}