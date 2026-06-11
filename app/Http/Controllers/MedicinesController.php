<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\MedicinesModel;
use Illuminate\Support\Str;

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
}