<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\UsersLogs;

class PrivateCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function home()
    {
        if (Auth::check() && Auth::user()->active) {
            $dateNow = date('Y-m-d H:i:s');
            $leadsDay = Leads::whereDate('created_at', date('Y-m-d'))->count();

            return view('system.home')->with('leadsDay', $leadsDay);
        } else {
            return redirect(route('login'));
        }
    }

    public function logout()
    {
        // Salvando log
        UsersLogs::create([
            'title' => 'Efetuando logout',
            'action' => 'Foi realizado o logout da plataforma.',
            'user_id' => Auth::user()->id
        ]);

        Auth::logout();
        return redirect(route('login'));
    }

    public function activities()
    {
        return view('system.activities')->with('logs', UsersLogs::orderBy('created_at', 'DESC')->get());
    }
}
