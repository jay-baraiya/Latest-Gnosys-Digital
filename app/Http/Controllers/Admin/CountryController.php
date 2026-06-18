<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $moduleName = 'Countries';
    protected $moduleUrl = 'countries.index';

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

        return view('country.index');
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
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        //
    }
}
