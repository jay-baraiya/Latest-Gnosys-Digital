<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $moduleName = 'Permissions';
    protected $moduleUrl = 'permissions.index';

    public function __construct()
    {
        view()->share([
            'moduleName' => $this->moduleName,
            'moduleUrl' => $this->moduleUrl,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }

        return view('permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
