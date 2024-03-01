<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Leads;

class LeadsCtrl extends Controller
{
    public function index()
    {
        return view('leads.index');
    }

    public function create()
    {      
        return view('leads.create');
    }

    public function store(Request $request)
    {     
        return redirect( route('index.leads') );
    }

    public function edit()
    {      
        return view('leads.edit');
    }

    public function update(Request $request, $id)
    {
        return view('leads.index');
    }

    public function destroy($id)
    {      
        return redirect( route('index.leads') );
    }
}