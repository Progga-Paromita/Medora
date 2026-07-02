<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data['getRecord'] = User::getRecord($request);
        $data['header_title'] = 'User Management';

        // Statistics for directory dashboard
        $data['totalUsers'] = User::where('is_deleted', 0)->count();
        $data['adminCount'] = User::where('is_deleted', 0)->where('is_role', 1)->count();
        $data['staffCount'] = User::where('is_deleted', 0)->where('is_role', 2)->count();

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add New User';
        return view('admin.users.create', $data);
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits_between:7,15',
            'password' => 'required|string|min:6|confirmed',
            'is_role' => 'required|in:1,2',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = new User();

        // Image upload
        if ($request->hasFile('profile_image')) {
            $ext = $request->file('profile_image')->getClientOriginalExtension();
            $file = $request->file('profile_image');

            $randomStr = Str::random(20);
            $filename = $randomStr . '.' . $ext;

            $file->move(public_path('uploads/profile/'), $filename);
            $user->profile_image = $filename;
        }

        $user->name = trim($request->name);
        $user->last_name = trim($request->last_name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(60);
        $user->phone = trim($request->phone);
        $user->is_role = $request->is_role;
        $user->status = 1; // Default to active

        $user->save();

        return redirect('admin/users')->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $data['user'] = $user;
        $data['header_title'] = 'User Details';
        return view('admin.users.show', $data);
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

        // Validation
        $request->validate([
            'name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|numeric|digits_between:7,15',
            'is_role' => 'required|in:1,2',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Profile image upload
        if ($request->hasFile('profile_image')) {
            // delete old image if exists
            if (!empty($user->profile_image) && file_exists(public_path('uploads/profile/' . $user->profile_image))) {
                unlink(public_path('uploads/profile/' . $user->profile_image));
            }

            $ext = $request->file('profile_image')->getClientOriginalExtension();
            $file = $request->file('profile_image');

            $randomStr = Str::random(20);
            $fileName = $randomStr . '.' . $ext;

            $file->move(public_path('uploads/profile/'), $fileName);
            $user->profile_image = $fileName;
        }

        // Update fields
        $user->name = trim($request->name);
        $user->last_name = trim($request->last_name);
        $user->email = trim($request->email);
        $user->phone = trim($request->phone);
        $user->is_role = trim($request->is_role);

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

    public function restore($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 0;
        $user->save();

        return redirect('admin/users')->with('success', 'User restored successfully');
    }
}