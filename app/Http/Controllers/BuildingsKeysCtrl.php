<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\BuildingsKeysRqt;
use App\Models\Buildings;
use App\Models\BuildingsKeys;
use App\Models\BuildingsPartners;
use App\Models\Companies;
use App\Models\UsersLogs;

class BuildingsKeysCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {
        return view('buildings.keys.index')->with('keys', BuildingsKeys::join('buildings', 'buildings.id', '=', 'buildings_keys.buildings_id')->select("buildings_keys.*")->orderBy('buildings_keys.active', 'desc')->orderBy('buildings.name', 'asc')->get());
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from buildings key all
        $keys = BuildingsKeys::orderBy('created_at', 'desc')
                                ->join('buildings', 'buildings_keys.buildings_id', '=', 'buildings.id')
                                ->select('buildings_keys.*', 'buildings.name as building');
        $recordsTotal = BuildingsKeys::count();

        // Search
        $search = $request->search;
        $keys = $keys->where( function($keys) use ($search){
            $keys->orWhere('buildings_keys.value', 'like', "%".$search."%");
            $keys->orWhere('buildings.name', 'like', "%".$search."%");
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $keys->count();
        $keys = $keys->skip($skip)->take($pageLength)->get();

        if( $keys->first() ){
            foreach($keys as $key) {
                // Status
                if( $key->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '<div class="d-flex justify-content-center align-items-center gap-2"><a href="'. route('buildings.keys.edit', $key->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a><a href="'. route('buildings.keys.destroy', $key->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>';
                
                // Array do emp
                $array[] = [
                    'empreendimento' => $key->building,
                    'value' => $key->value,
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
        $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        $buildings = Buildings::where('active', 1)->orderBy('name', 'asc')->get();
        
        foreach($buildings as $building){
            $building->companie = BuildingsPartners::where('buildings_id', $building->id)->where('main', 1)->first()->companies_id;
        }
        
        foreach($companies as $companie){
            foreach($buildings as $building){ 
                if( $companie->id == $building->companie ){
                    $array[$companie->name][] = $building;
                }
            }
        } 

        return view('buildings.keys.create')->with('array', isset($array) ? $array : null);
    }

    public function store(BuildingsKeysRqt $request)
    {      
        BuildingsKeys::create([
            'value' => $request->value, 
            'buildings_id' => $request->building, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova chave',
            'description' => 'Foi realizado o cadastro de uma nova chave: ' . $request->value . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('buildings.keys.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        $buildings = Buildings::where('active', 1)->orderBy('name', 'asc')->get();

        foreach($buildings as $building){
            $building->companie = BuildingsPartners::where('buildings_id', $building->id)->where('main', 1)->first()->companies_id;
        }
        
        foreach($companies as $companie){
            foreach($buildings as $building){ 
                if( $companie->id == $building->companie ){
                    $array[$companie->name][] = $building;
                }
            }
        } 

        return view('buildings.keys.edit')->with('key', BuildingsKeys::find($id))->with('array', isset($array) ? $array : null);
    } 

    public function update(BuildingsKeysRqt $request, string $id)
    {
        BuildingsKeys::find($id)->update([
            'value' => $request->value, 
            'buildings_id' => $request->building, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da chave',
            'description' => 'Foi realizado a atualização das informações da chave: ' . $request->value . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('buildings.keys.index')->with('edit', true);
    }

    public function destroy(string $id)
    {     
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de chave',
            'description' => 'Foi realizado a exclusão da chave: ' .  BuildingsKeys::find($id)->value . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        BuildingsKeys::find($id)->delete();
        return redirect()->route('buildings.keys.index')->with('destroy', true);
    }
}
