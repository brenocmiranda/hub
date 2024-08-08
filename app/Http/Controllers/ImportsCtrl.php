<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
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
        $this->middleware('can:access_komuh');
        $this->middleware('can:imports_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:imports_create', ['only' => ['create', 'store']]);
        $this->middleware('can:imports_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:imports_destroy', ['only' => ['destroy']]);
	}

    public function index()
    {
        return view('imports.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from imports all
        $imports = UsersImports::orderBy('created_at', 'desc');
        $recordsTotal = UsersImports::count();

        // Search
        $search = $request->search;
        $imports = $imports->where( function($imports) use ($search){
            $imports->orWhere('users_imports.name', 'like', "%".$search."%");
            $imports->orWhere('users_imports.type', 'like', "%".$search."%");
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $imports->count();
        $imports = $imports->skip($skip)->take($pageLength)->get();

        if( $imports->first() ){
            foreach($imports as $import) {
                // Status
                if( $import->status === "Na fila"){
                    $status = '<div class="badge text-bg-secondary">' . $import->status . '</div>';
                }else if( $import->status === "Executando" ) {
                    $status = '<div class="badge text-bg-primary">' . $import->status . '</div>';
                }else if( $import->status === "Importando" ) {
                    $status = '<div class="badge text-bg-dark">' . $import->status . '</div>';
                }else if( $import->status === "Pronto" ) {
                    $status = '<div class="badge text-bg-success">' . $import->status . '</div>';
                }else {
                    $status = $import->status;
                }

                // Type
                if ($import->type === 'integrations'){
                    $type = 'Integrações';
                } else if($import->type === 'buildings') {
                    $type = 'Empreendimentos';
                } else {
                    $type = 'Leads';
                }

                // Operações
                $operations = '';
                if (Gate::any(['imports_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('imports_destroy') ) {
                        $operations .= '<a href="'. route('imports.destroy', $import->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }

                $view = $import->status === "Pronto" ? '<a href="'. route('leads.index') .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualizar leads"><i class="bi bi-eye"></i></a>' : "";
                $operations = '<div class="d-flex justify-content-center align-items-center gap-2">'. $view .'</div>';
                
                // Array do emp
                $array[] = [
                    'data' => $import->created_at->format("d/m/Y H:i:s"),
                    'name' => $import->name,
                    'type' => $type,
                    'status' => $status,
                    'operations' => $operations
                ];
            }
        } else {
            $array = [];
        }

        return response()->json(["total" => $recordsTotal, "totalNotFiltered" => $recordsFiltered, 'rows' => $array], 200);
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
        /* Remove file
        $report = UsersImports::find($id);
        $file = storage_path('app/public/imports/') . $report->name;
        if ( file_exists($file) ) {
            unlink($file);
        }*/

        UsersImports::find($id)->delete();
        return redirect()->route('imports.index')->with('destroy', true);
    }
}