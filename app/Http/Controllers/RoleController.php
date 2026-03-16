<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = \App\Models\Permission::orderBy('name')
            ->get(['id', 'name', 'slug']);

        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');

        $tableNames = collect($tables)->map(function ($table) {
            return array_values((array) $table)[0];
        });

        return view('roles.create', compact('permissions', 'tableNames'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'table_name' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Create role
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $permissionIds = $request->input('permissions', []);

        $syncData = [];

        foreach ($permissionIds as $permissionId) {
            $syncData[$permissionId] = [
                'table_name' => $request->table_name
            ];
        }

        // Save permissions with table name
        $role->permissions()->sync($syncData);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('roles.show', compact('role'));
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::orderBy('name')->get();

        // get database tables
        $tableNames = DB::select('SHOW TABLES');

        $tableNames = collect($tableNames)->map(function ($table) {
            return array_values((array) $table)[0];
        });

        // get selected table from pivot table
        $tableName = $role->permissions()->first()->pivot->table_name ?? null;

        // get selected permission ids
        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

        return view('roles.edit', compact(
            'role',
            'permissions',
            'tableNames',
            'tableName',
            'rolePermissions'
        ));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'table_name' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);

        // update role info
        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $permissionIds = $request->permissions ?? [];

        $syncData = [];

        foreach ($permissionIds as $permissionId) {
            $syncData[$permissionId] = [
                'table_name' => $request->table_name
            ];
        }

        // update pivot
        $role->permissions()->sync($syncData);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
