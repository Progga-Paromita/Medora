<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
{
    $data['getRecord'] = User::getRecord();
    $data['header_title'] = 'User List';

    return view('admin.users.index', $data);
}

public function create()
{
    $data['header_title'] = 'Add New User';

    return view('admin.users.create', $data);
}

public function store(Request $request)
{
    $user = new User();

    // Image upload
    if ($request->hasFile('profile_image')) {

        $ext = $request->file('profile_image')->getClientOriginalExtension();
        $file = $request->file('profile_image');

        $randomStr = Str::random(20);
        $filename = $randomStr . '.' . $ext;

        $file->move('uploads/profile/', $filename);

        $user->profile_image = $filename;
    }

    $user->name = $request->name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->remember_token = Str::random(60);
    $user->phone = $request->phone;
    $user->is_role = $request->is_role;
    $user->status = $request->status;

    $user->save();

    return redirect('admin/users')->with('success', 'User added successfully');
}

public function show($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.show', compact('user'));
}

public function edit($id)
{
    $data['getRecord'] = User::getSingleRecord($id);
    $data['header_title'] = 'Edit User';

    return view('admin.users.edit', $data);
}

public function update(Request $request, $id)
{
    $user = User::getSingleRecord($id);

    // Profile image upload
    if ($request->hasFile('profile_image')) {

        // delete old image if exists
        if (!empty($user->profile_image) && file_exists('uploads/profile/' . $user->profile_image)) {
            unlink('uploads/profile/' . $user->profile_image);
        }

        $ext = $request->file('profile_image')->getClientOriginalExtension();
        $file = $request->file('profile_image');

        $randomStr = Str::random(20);
        $fileName = $randomStr . '.' . $ext;

        $file->move('uploads/profile', $fileName);

        $user->profile_image = $fileName;
    }

    // Update fields
    $user->name = trim($request->name);
    $user->last_name = trim($request->last_name);
    $user->email = trim($request->email);

    if ($request->password) {
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(60);
    }

    $user->phone = trim($request->phone);
    $user->is_role = trim($request->is_role);
    $user->status = trim($request->status);

    $user->save();

    return redirect('admin/users')->with('success', 'User updated successfully');
}
public function delete($id)
{
    $user = User::getSingleRecord($id);
    $user->is_deleted = 1;
    $user->save();

    return redirect('admin/users')->with('success', 'User deleted successfully');
}
}