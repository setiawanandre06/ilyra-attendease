<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedSorts = ['name', 'created_at', 'users_count'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $dir = $request->get('dir') === 'desc' ? 'desc' : 'asc';

        $departments = Department::with('manager')
            ->withCount('users')
            ->when($request->get('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString(); // ← penting, agar search & sort tetap saat pindah halaman

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('employee')->get();

        return view('admin.departments.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate form
        $request->validate([
            'name' => 'required|string|unique:departments,name|max:255',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        // create department
        Department::create([
            'name' => $request->name,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // show the details of a department
        $department = Department::findOrFail($id);

        return view('admin.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // show edit form for a department
        $department = Department::findOrFail($id);
        $users = User::role('employee')->get();

        return view('admin.departments.edit', compact('department', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate form
        $request->validate([
            'name' => 'required|string|unique:departments,name,'.$id.'|max:255',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        // find department
        $department = Department::findOrFail($id);

        // update department
        $department->update([
            'name' => $request->name,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}
