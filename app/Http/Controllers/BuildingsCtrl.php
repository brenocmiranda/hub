<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings;
use App\Models\Companies;

class BuildingsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('buildings.index')->with('buildings', Buildings::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('buildings.create')->with('companies', Companies::where('active', 1)->get());
    }

    public function store(Request $request)
    {     
        return redirect( route('index.buildings') );
    }

    public function edit()
    {      
        return view('buildings.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect( route('index.buildings') );
    }

    public function destroy($id)
    {      
        return redirect( route('index.buildings') );
    }
}
