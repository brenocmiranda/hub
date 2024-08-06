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
            $requestSuccess = DB::table('job_batches')->where('pending_jobs', '=', 0)->count();
            $requestFail = DB::table('job_batches')->where('pending_jobs', '>', 0)->where('failed_jobs', '>', 0)->count();
            $requestPending = DB::table('job_batches')->count() - $requestSuccess - $requestFail;

            return view('system.home')->with('requestPending', $requestPending)->with('requestSuccess', $requestSuccess)->with('requestFail', $requestFail);
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
            'users_id' => Auth::user()->id
        ]);

        Auth::logout();
        return redirect(route('login'));
    }

    public function activities()
    {
        return view('system.activities')->with('logs', UsersLogs::where('users_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get());
    }

    public function unauthorized(){
        return view('system.unauthorized');
    }
}
