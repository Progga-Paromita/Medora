<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Administrators List';
        return view('admin.users.index', $data);
    }

    // Show create form
    public function create()
    {
        $data['header_title'] = 'Add New Administrator';
        return view('admin.users.create', $data);
    }

    public function store(Request $request)
    {
        // Store user logic
    }

    public function show($id)
    {
        return view('admin.users.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.users.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update user logic
    }

    public function destroy($id)
    {
        // Delete user logic
    }
}
