<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\CompaniesRqt;
use App\Models\Companies;

class CompaniesCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('companies.index')->with('companies', Companies::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('companies.create');
    }

    public function store(CompaniesRqt $request)
    {      
        Companies::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        return redirect()->route('index.companies')->with('create', true);
    }

    public function edit($id)
    {      
        return view('companies.edit')->with('companie', Companies::find($id));
    } 

    public function update(CompaniesRqt $request, $id)
    {
        Companies::find($id)->update([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        return redirect()->route('index.companies')->with('edit', true);
    }

    public function destroy($id)
    {      
        Companies::find($id)->delete();
        return redirect()->route('index.companies')->with('destroy', true);
    }
}
