<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Companies;

class BuildingsCtrl extends Controller
{
    public function index()
    {
        return view('buildings.index');
    }

    public function create()
    {      
        return view('buildings.create');
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
        return view('buildings.index');
    }

    public function destroy($id)
    {      
        return redirect( route('index.buildings') );
    }
}
