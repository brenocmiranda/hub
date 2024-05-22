<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Leads;
use App\Models\LeadsFields;

class ImportacoesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}

    public function indexLeads()
    {
        return view('imports.leads');
    }

    public function generateLeads(Request $request)
    {   
        Excel::import(new UsersImport, 'users.xlsx');
        return redirect()->route('imports.leads')->with('imports', true);
    }
}
