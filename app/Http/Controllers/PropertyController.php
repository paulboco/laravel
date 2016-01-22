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
        $properties = Property::sortByStreetAddress()
            ->whereActive()
            ->get();

        return view('property.index', compact('properties'));
    }
}
