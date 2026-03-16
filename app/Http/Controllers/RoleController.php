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
            'table_names' => 'required|array',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = \App\Models\Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $syncData = [];

        foreach ($request->table_names as $table) {

            foreach ($request->permissions as $permissionId) {

                $syncData[] = [
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'table_name' => $table,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        \Illuminate\Support\Facades\DB::table('role_permission')->insert($syncData);

        return redirect()->route('roles.index')
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

    public function edit($id)
    {
        $role = \App\Models\Role::findOrFail($id);

        $permissions = \App\Models\Permission::orderBy('name')->get();

        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');

        $tableNames = collect($tables)->map(function ($table) {
            return array_values((array) $table)[0];
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        $selectedTables = $role->permissions
            ->pluck('pivot.table_name')
            ->unique()
            ->toArray();

        return view('roles.edit', compact(
            'role',
            'permissions',
            'tableNames',
            'rolePermissions',
            'selectedTables'
        ));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'table_names' => 'required|array',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = \App\Models\Role::findOrFail($id);

        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        \Illuminate\Support\Facades\DB::table('role_permission')
            ->where('role_id', $role->id)
            ->delete();

        $insertData = [];

        foreach ($request->table_names as $table) {

            foreach ($request->permissions as $permissionId) {

                $insertData[] = [
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                    'table_name' => $table,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        \Illuminate\Support\Facades\DB::table('role_permission')
            ->insert($insertData);

        return redirect()->route('roles.index')
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
