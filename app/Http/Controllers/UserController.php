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
        $data['header_title'] = 'User List';
        $data['users'] = User::all();

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $data['header_title'] = 'Add New User';
        return view('admin.users.create', $data);
    }

    public function store(Request $request)
{
    //dd($request->all());

    $user = new User();
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
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->phone = $request->phone;
        $user->is_role = $request->is_role;
        $user->status = $request->status;

        $user->save();

        return redirect('admin/users')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('admin/users')->with('success', 'User deleted successfully');
    }
}