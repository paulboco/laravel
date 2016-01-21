<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('property.index', [
            'properties' => Property::orderBy('property_street_name')->get(),
        ]);
    }
}
