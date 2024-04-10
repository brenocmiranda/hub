<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pipelines;
use App\Models\UsersLogs;

class PipelinesCtrl
{
    public function index()
    {
        return view('leads.pipelines.index')->with('pipelines', Pipelines::all()->reverse());
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        // Salvando log
        UsersLogs::create([
            'title' => 'Visualização de pipeline',
            'description' => 'Foi realizada a visualização das informações da pipeline ' . Pipelines::find($id)->statusCode == 1 ? "Disparo de e-mail" : (Pipelines::find($id)->statusCode == 2 ? "Google Sheets" : ( Pipelines::find($id)->RelationIntegrations ? Pipelines::find($id)->RelationIntegrations->name : "" )) . ' do lead ' . Pipelines::find($id)->RelationLeads->name . '.',
            'action' => 'show',
            'user_id' => Auth::user()->id
        ]);

        return view('leads.pipelines.show')->with('pipeline', Pipelines::find($id));
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
