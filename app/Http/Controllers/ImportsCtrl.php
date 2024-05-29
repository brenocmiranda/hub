<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Imports\LeadsImport;
use App\Models\UsersLogs;
use App\Models\UsersImports;

class ImportsCtrl extends Controller
{

    public function __construct(){
		$this->middleware('auth');
	}

    public function index()
    {
        return view('imports.index')->with('imports', UsersImports::where('users_id', Auth::user()->id));
    }

    public function create()
    {
        return view('imports.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
