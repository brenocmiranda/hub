<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersLogs;

class PrivateCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function home()
    {
        //if (Auth::check() && Auth::user()->active) {
        return view('system.home');
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
        return view('system.activities')->with('logs', UsersLogs::where('users_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(8));
    }
}
