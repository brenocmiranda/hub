<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Leads;

class DashboardsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function indexGeneral()
    {   
        if( Gate::check('access_komuh') ) {
            $leads = Leads::all();
        } else {
            $leads = Leads::where('companies_id', Auth::user()->companies_id)->get();
        } 
        
        return view('dashboards.indexGeneral')->with('leads', $leads);
    }

    public function indexBuildings()
    {   
        if( Gate::check('access_komuh') ) {
            $leads = Leads::all();
        } else {
            $leads = Leads::where('companies_id', Auth::user()->companies_id)->get();
        } 

        return view('dashboards.indexBuildings')->with('leads', $leads);
    }
}
