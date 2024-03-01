<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class DashboardsCtrl extends Controller
{
    public function index()
    {
        return view('dashboards.index');
    }
}
