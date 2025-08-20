<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class MstRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->filled('kode')) {
            $query->where('role_code', 'like', '%' . $request->kode . '%');
        }

        $roleUsers = $query->paginate(10);

        return view('mst_role.index', compact('roleUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mst_role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_code' => 'required|string|max:10|unique:m_role,role_code',
            'role_name' => 'required|string|max:30',

        ]);

        Role::create($request->only(['role_code', 'role_name']));

        return redirect()->route('master-role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($role_code)
    {
        $role = Role::findOrFail($role_code);
        return view('mst_role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role_code)
    {
        $request->validate([
            'role_code' => 'required|string|max:10',
            'role_name' => 'required|string|max:30',
        ]);

        $role = Role::findOrFail($role_code);
        $role->update([
            'role_code' => $request->role_code,
            'role_name' => $request->role_name,
        ]);

        return redirect()->route('master-role.index')->with('success', 'Role berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role_code)
    {
        $role = Role::findOrFail($role_code);
        $role->delete();

        return redirect()->route('master-role.index')->with('success', 'Data berhasil dihapus');
    }
}
