<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ImportsRqt;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LeadsImport;
use App\Models\UsersLogs;
use App\Models\UsersImports;
use App\Models\Companies;
use App\Models\Buildings;
use App\Models\LeadsOrigins;

class ImportsCtrl extends Controller
{

    public function __construct(){
		$this->middleware('auth');
	}

    public function index()
    {
        return view('imports.index')->with('imports', UsersImports::where('users_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get());
    }

    public function create()
    {
        return view('imports.create');
    }

    public function store(ImportsRqt $request)
    {   
        if($request->hasFile('fileImport')){
            // Get filename with the extension
            $filenameWithExt = $request->file('fileImport')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('fileImport')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('fileImport')->storeAs('public/imports', $fileNameToStore);  
        }

        // Create data in import
        $type = $request->input('type');
        $import = UsersImports::create([
            'name' => $fileNameToStore, 
            'type' => $type, 
            'status' => 'Na fila',
            'users_id' => Auth::user()->id,
        ]);
        
        // Campos Obrigatórios
        $fieldsMandatory = $request->input('fieldsMandatory');
        // Get ID Companie
        $companie = Companies::where('name', $fieldsMandatory[3])->first();
        $fieldsMandatory[3] = $companie ? $companie->id : 1;
        // Get ID Origin Lead
        $origin = LeadsOrigins::where('name', $fieldsMandatory[4])->first();
        $fieldsMandatory[4] = $origin ? $origin->id : 1;
        // Get ID Building
        $building = Buildings::where('name', $fieldsMandatory[5])->first();
        $fieldsMandatory[5] = $building ? $building->id : 1;

        // Campos Opcionais
        $fieldsOptionalsName = $request->input('fieldsOptionalsName');
        $fieldsOptionalsValue = $request->input('fieldsOptionalsValue');

        Excel::queueImport(new LeadsImport($import, $fieldsMandatory, $fieldsOptionalsName, $fieldsOptionalsValue), storage_path('app/public/imports/'.$fileNameToStore));

        // Salvando log
        UsersLogs::create([
            'title' => 'Executando importação',
            'description' => 'Foi realizada a importação de um arquivo de '. $type .'.',
            'action' => 'imports',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('imports.index')->with('imports', true);
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
