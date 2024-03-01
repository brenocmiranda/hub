<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class IntegrationsCtrl extends Controller
{
    public function index()
    {
        return view('integrations.index');
    }

    public function create()
    {      
        return view('integrations.create');
    }

    public function store(Request $request)
    {     
        return redirect( route('index.integrations') );
    }

    public function edit()
    {      
        return view('integrations.edit');
    }

    public function update(Request $request, $id)
    {
        return view('integrations.index');
    }

    public function destroy($id)
    {      
        return redirect( route('index.integrations') );
    }
}
