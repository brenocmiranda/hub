<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\IntegrationsRqt;
use App\Models\Integrations;

class IntegrationsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('integrations.index')->with('integrations', Integrations::orderBy('name', 'asc')->get());
    }

    public function create()
    {      
        return view('integrations.create');
    }

    public function store(IntegrationsRqt $request)
    {      
        Integrations::create([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
            'url' => $request->url,
            'user' => $request->user ? $request->user : "",
            'password' => $request->password ? $request->password : "",
            'token' => $request->token ? $request->token : "",
            'header' => $request->header ? $request->header : "",
        ]);

        return redirect()->route('integrations.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        return view('integrations.edit')->with('integration', Integrations::find($id));
    } 

    public function update(IntegrationsRqt $request, string $id)
    {
        Integrations::find($id)->update([
            'name' => $request->name, 
            'slug' => $request->slug, 
            'active' => $request->active,
            'url' => $request->url,
            'user' => $request->user ? $request->user : "",
            'password' => $request->password ? $request->password : "",
            'token' => $request->token ? $request->token : "",
            'header' => $request->header ? $request->header : "",
        ]);

        return redirect()->route('integrations.index')->with('edit', true);
    }

    public function destroy(string $id)
    {      
        Integrations::find($id)->delete();
        return redirect()->route('integrations.index')->with('destroy', true);
    }
}
