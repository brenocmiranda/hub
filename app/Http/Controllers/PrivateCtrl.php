<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Companies;
use App\Models\Leads;

class PrivateCtrl extends Controller
{
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
        Auth::logout();
        return redirect(route('login'));
    }
}
