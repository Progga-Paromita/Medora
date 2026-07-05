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
    public function list(Request $request): View
    {
        $data['getRecord'] = MedicinesModel::getRecord($request);
        $data['header_title'] = 'Medicines List';

        // Prepopulate suppliers for filters dropdown (all non-deleted)
        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)->get();

        // Statistics for medicines dashboard
        $data['totalMedicines'] = MedicinesModel::where('is_deleted', 0)->count();
        $data['newMedicinesThisMonth'] = MedicinesModel::where('is_deleted', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $data['deletedMedicines'] = MedicinesModel::where('is_deleted', 1)->count();

        return view('admin.medicines.list', $data);
    }

    public function create(): View
    {
        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Add New Medicine';

        return view('admin.medicines.add', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'generic_name' => 'required|string|max:100',
            'packaging' => 'required|in:Tablet,Capsule,Syrup,Injection,Ointment,Cream,Drops,Suspension,Powder,Inhaler,Other',
            'supplier_id' => 'required|exists:suppliers,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sku' => 'nullable|string|max:50',
            'strength' => 'nullable|string|max:50',
            'temperature_control' => 'nullable|string|max:50',
            'prescription_required' => 'nullable|in:0,1',
        ]);

        $save = new MedicinesModel();

        // Profile Image
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $ext = $file->getClientOriginalExtension();

            $randomStr = Str::random(20);
            $fileName = $randomStr . '.' . $ext;

            $file->move(public_path('uploads/medicines/'), $fileName);
            $save->profile_image = $fileName;
        }

        // Fields
        $save->name = trim($request->name);
        $save->generic_name = trim($request->generic_name);
        $save->packaging = trim($request->packaging);
        $save->supplier_id = trim($request->supplier_id);
        $save->strength = trim($request->strength);
        $save->category = trim($request->packaging); // Sync category with packaging
        $save->sku = trim($request->sku);
        $save->temperature_control = trim($request->temperature_control);
        $save->prescription_required = trim($request->prescription_required ?? 0);
        $save->save();

        return redirect('admin/medicines')->with('success', 'Medicine added successfully');
    }

    public function show($id): View
    {
        $medicine = MedicinesModel::findOrFail($id);
        $data['medicine'] = $medicine;
        $data['header_title'] = 'Medicine Details';
        return view('admin.medicines.show', $data);
    }

    public function edit($id): View
    {
        $data['getRecord'] = MedicinesModel::getSingleRecord($id);
        $data['getSuppliers'] = SuppliersModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Edit Medicine';

        return view('admin.medicines.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $medicines = MedicinesModel::getSingleRecord($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'generic_name' => 'required|string|max:100',
            'packaging' => 'required|in:Tablet,Capsule,Syrup,Injection,Ointment,Cream,Drops,Suspension,Powder,Inhaler,Other',
            'supplier_id' => 'required|exists:suppliers,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sku' => 'nullable|string|max:50',
            'strength' => 'nullable|string|max:50',
            'temperature_control' => 'nullable|string|max:50',
            'prescription_required' => 'nullable|in:0,1',
        ]);

        // Profile Image Upload
        if ($request->hasFile('profile_image')) {
            // delete old image if exists
            if (!empty($medicines->profile_image) && file_exists(public_path('uploads/medicines/' . $medicines->profile_image))) {
                unlink(public_path('uploads/medicines/' . $medicines->profile_image));
            }

            $file = $request->file('profile_image');
            $ext = $file->getClientOriginalExtension();

            $randomStr = Str::random(20);
            $fileName = $randomStr . '.' . $ext;

            $file->move(public_path('uploads/medicines/'), $fileName);
            $medicines->profile_image = $fileName;
        }

        // Update fields
        $medicines->name = trim($request->name);
        $medicines->generic_name = trim($request->generic_name);
        $medicines->packaging = trim($request->packaging);
        $medicines->supplier_id = trim($request->supplier_id);
        $medicines->strength = trim($request->strength);
        $medicines->category = trim($request->packaging);
        $medicines->sku = trim($request->sku);
        $medicines->temperature_control = trim($request->temperature_control);
        $medicines->prescription_required = trim($request->prescription_required ?? 0);
        $medicines->save();

        return redirect('admin/medicines')->with('success', 'Medicine updated successfully');
    }

    public function delete($id)
    {
        $medicines = MedicinesModel::getSingleRecord($id);
        $medicines->is_deleted = 1;
        $medicines->save();

        return redirect('admin/medicines')->with('success', 'Medicine deleted successfully');
    }

    public function restore($id)
    {
        $medicine = MedicinesModel::findOrFail($id);
        $medicine->is_deleted = 0;
        $medicine->save();

        return redirect('admin/medicines')->with('success', 'Medicine restored successfully');
    }

    public function list_stock(Request $request): View
    {
        $data['getRecord'] = StockModel::getRecord($request);
        $data['medicines'] = MedicinesModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Stock List';

        return view('admin.stock.list', $data);
    }

    public function add_stock(): View
    {
        $data['medicines'] = MedicinesModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Add New Stock';

        return view('admin.stock.add', $data);
    }

    public function store_stock(Request $request): RedirectResponse
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'batch_id' => 'required|string|max:50',
            'expiry_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:0',
            'rate' => 'required|numeric|min:0.01',
            'mrp' => 'required|numeric|min:0.01|gte:rate',
        ], [
            'expiry_date.after' => 'The expiry date must be a future date.',
            'mrp.gte' => 'The MRP must be greater than or equal to the purchase rate.',
        ]);

        $save = new StockModel();
        $save->medicine_id = trim($request->medicine_id);
        $save->batch_id = trim($request->batch_id);
        $save->expiry_date = trim($request->expiry_date);
        $save->quantity = trim($request->quantity);
        $save->mrp = trim($request->mrp);
        $save->rate = trim($request->rate);
        $save->is_deleted = 0;
        $save->save();

        return redirect('admin/stocks')->with('success', 'Stock added successfully');
    }

    public function edit_stock($id): View
    {
        $data['getRecord'] = StockModel::getSingleRecord($id);
        $data['medicines'] = MedicinesModel::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $data['header_title'] = 'Edit Stock';

        return view('admin.stock.edit', $data);
    }

    public function update_stock(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'batch_id' => 'required|string|max:50',
            'expiry_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:0',
            'rate' => 'required|numeric|min:0.01',
            'mrp' => 'required|numeric|min:0.01|gte:rate',
        ], [
            'expiry_date.after' => 'The expiry date must be a future date.',
            'mrp.gte' => 'The MRP must be greater than or equal to the purchase rate.',
        ]);

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