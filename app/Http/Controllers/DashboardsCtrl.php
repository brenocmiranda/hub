<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Leads;

class DashboardsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        $requestPending = DB::table('job_batches')->whereNull('pending_jobs')->count();
        $requestSuccess = DB::table('job_batches')->whereNull('cancelled_at')->count();
        $requestFail = DB::table('job_batches')->whereNotNull('cancelled_at')->count();

        return view('dashboards.index')->with('requestPending', $requestPending)->with('requestSuccess', $requestSuccess)->with('requestFail', $requestFail);
    }
}
