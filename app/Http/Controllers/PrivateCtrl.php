<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\UsersLogs;
use App\Models\Pipelines;

class PrivateCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function home()
    {
        if (Auth::check() && Auth::user()->active) {
            $leadsDay = Leads::whereDate('created_at', date('Y-m-d'))->count();
            $requestSuccess = DB::table('job_batches')->whereNull('cancelled_at')->whereDate(DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at), '%Y-%m-%d')"), date('Y-m-d'))->count();
            $requestFail = DB::table('job_batches')->whereNotNull('cancelled_at')->whereDate(DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at), '%Y-%m-%d')"), date('Y-m-d'))->count();

            return view('system.home')->with('leadsDay', $leadsDay)->with('requestSuccess', $requestSuccess)->with('requestFail', $requestFail);
        } else {
            return redirect(route('login'));
        }
    }

    public function logout()
    {
        // Salvando log
        UsersLogs::create([
            'title' => 'Efetuando logout',
            'description' => 'Foi realizado o logout da plataforma.',
            'action' => 'logout',
            'user_id' => Auth::user()->id
        ]);

        Auth::logout();
        return redirect(route('login'));
    }

    public function activities()
    {
        return view('system.activities')->with('logs', UsersLogs::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get());
    }
}
