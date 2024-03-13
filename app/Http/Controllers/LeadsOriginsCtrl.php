<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\LeadsOriginsRqt;
use App\Models\LeadsOrigins;

class LeadsOriginsCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('leads.origins.index')->with('origins', LeadsOrigins::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('leads.origins.create');
    }

    public function store(LeadsOriginsRqt $request)
    {      
        LeadsOrigins::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        return redirect()->route('index.leads.origins')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('leads.origins.edit')->with('origin', LeadsOrigins::find($id));
    } 

    public function update(LeadsOriginsRqt $request, string $id)
    {
        LeadsOrigins::find($id)->update([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
        ]);

        return redirect()->route('index.leads.origins')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        LeadsOrigins::find($id)->delete();
        return redirect()->route('index.leads.origins')->with('destroy', true);
    }
}
