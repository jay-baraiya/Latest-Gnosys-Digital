<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {

        $digitalproducts = DigitalProduct::query()->where('status', 1)->orderBy('id', 'DESC')->limit(4)->get();

        return view('index', compact('digitalproducts'));
    }
}
