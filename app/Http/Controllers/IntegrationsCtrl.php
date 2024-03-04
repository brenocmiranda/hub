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

        return redirect()->route('index.integrations')->with('create', true);
    }

    public function edit($id)
    {      
        return view('integrations.edit')->with('integration', Integrations::find($id));
    } 

    public function update(IntegrationsRqt $request, $id)
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

        return redirect()->route('index.integrations')->with('edit', true);
    }

    public function destroy($id)
    {      
        Integrations::find($id)->update([ 'active' => 0 ]);
        return redirect()->route('index.integrations')->with('destroy', true);
    }
}
