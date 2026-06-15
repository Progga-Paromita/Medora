<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\MedicinesModel;
use Illuminate\Support\Str;
use App\Models\SuppliersModel;
use App\Models\StockModel;

class MedicinesController extends Controller
{
    public function list(): View
    {
        $data['getRecord'] = MedicinesModel::getRecord();
        $data['header_title'] = 'Medicines List';

        return view('admin.medicines.list', $data);
    }

    public function create(): View
    {
        $data['getSuppliers'] = SuppliersModel::getRecord();
        $data['header_title'] = 'Add New Medicine';

        return view('admin.medicines.add', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $save = new MedicinesModel();

        // Profile Image
        if ($request->hasFile('profile_image')) {

            $file = $request->file('profile_image');
            $ext = $file->getClientOriginalExtension();

            $randomStr = Str::random(20);
            $fileName = $randomStr . '.' . $ext;

            $file->move('uploads/medicines/', $fileName);

            $save->profile_image = $fileName;
        }

        // Fields
        $save->name = trim($request->name);
        $save->packaging = trim($request->packaging);
        $save->generic_name = trim($request->generic_name);
        $save->supplier_id = trim($request->supplier_id);

        $save->save();

        return redirect('admin/medicines')->with('success', 'Medicine added successfully');
    }


    public function edit($id): View
{
    $data['getRecord'] = MedicinesModel::getSingleRecord($id);
    $data['header_title'] = 'Edit Medicine';

    return view('admin.medicines.edit', $data);
}

public function update(Request $request, $id)
{
    $medicines = MedicinesModel::getSingleRecord($id);

    // Profile Image Upload
    if ($request->hasFile('profile_image')) {

        // delete old image if exists
        if (!empty($medicines->profile_image) &&
            file_exists('uploads/medicines/' . $medicines->profile_image)) {
            unlink('uploads/medicines/' . $medicines->profile_image);
        }

        $file = $request->file('profile_image');
        $ext = $file->getClientOriginalExtension();

        $randomStr = \Illuminate\Support\Str::random(20);
        $fileName = $randomStr . '.' . $ext;

        $file->move('uploads/medicines', $fileName);

        $medicines->profile_image = $fileName;
    }

    // Update fields
    $medicines->name = trim($request->name);
    $medicines->packaging = trim($request->packaging);
    $medicines->generic_name = trim($request->generic_name);
    $medicines->supplier_id = trim($request->supplier_id);

    $medicines->save();

    return redirect('admin/medicines')->with('success', 'Medicine updated successfully');
}
public function delete($id)
{
    $medicines = MedicinesModel::getSingleRecord($id);

    $medicines->is_deleted = 1;
    $medicines->save();

    return redirect('admin/medicines')
        ->with('success', 'Medicine deleted successfully');
}

public function list_stock(): View
{
    $data['getRecord'] = StockModel::getRecord();
    $data['header_title'] = 'Stock List';

    return view('admin.stock.list', $data);
}


public function add_stock(): View
{
    $data['medicines']= MedicinesModel::getRecord();
    $data['header_title'] = 'Add New Stock';

    return view('admin.stock.add', $data);
}

public function store_stock(Request $request): RedirectResponse
{
   // dd($request->all());

    $save = new StockModel();

    $save->medicine_id = trim($request->medicine_id);
    $save->batch_id = trim($request->batch_id);
    $save->expiry_date = trim($request->expiry_date);
    $save->quantity = trim($request->quantity);
    $save->mrp = trim($request->mrp);
    $save->rate = trim($request->rate);

    $save->save();

    return redirect('admin/stocks')->with('success', 'Stock added successfully');
}
public function edit_stock($id): View
{
    $data['getRecord'] = StockModel::getSingleRecord($id);
    $data['medicines'] = MedicinesModel::getRecord();
    $data['getSuppliers'] = SuppliersModel::getRecord(); // ✅ ADD THIS

    $data['header_title'] = 'Edit Stock';

    return view('admin.stock.edit', $data);
}
public function update_stock(Request $request, $id): RedirectResponse
{
    $save = StockModel::getSingleRecord($id);

    $save->medicine_id = trim($request->medicine_id);
    $save->batch_id = trim($request->batch_id);
    $save->expiry_date = trim($request->expiry_date);
    $save->quantity = trim($request->quantity);
    $save->mrp = trim($request->mrp);
    $save->rate = trim($request->rate);

    $save->save();

    return redirect('admin/stocks')->with('success', 'Stock updated successfully');
}
public function delete_stock($id): RedirectResponse
{
    $save = StockModel::getSingleRecord($id);
    $save->is_deleted = 1;
    $save->save();

    return redirect('admin/stocks')->with('success', 'Stock deleted successfully');
}

}