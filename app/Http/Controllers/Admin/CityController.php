<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $moduleName = 'Cities';
    protected $moduleUrl = 'cities.index';

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

        return view('city.index');
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
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }
}
