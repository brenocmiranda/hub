<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadsExport;
use App\Exports\BuildingsExport;
use App\Exports\IntegrationsExport;
use App\Models\Buildings;
use App\Models\BuildingsPartners;
use App\Models\Companies;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;
use App\Models\UsersReports;

class ReportsCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}

    public function index()
    {
        return view('reports.index')->with('reports', UsersReports::where('users_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get());
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

        return view('reports.create')->with('origins', LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get())->with('array', isset($array) ? $array : null);
    }

    public function store(Request $request)
    {      
        foreach($request->all() as $index => $element){
            if( $element === "on") {
                $items[] = $index;
            }
        }

        $format = $request->input('format');
        $type = $request->input('type');
        $nameFile = $type . '-' . rand() . '.' . $format;
        $path = 'public/exports';
        $pathFile = $path . '/' . $nameFile;

        // Create data in reports
        $report = UsersReports::create([
            'name' => $nameFile, 
            'type' => $type, 
            'status' => 'Na fila',
            'users_id' => Auth::user()->id,
        ]);

        if( $type === 'leads' ){
            // Leads 
            $dataInicial = $request->input('dataInicial');
            $dataFinal = $request->input('dataFinal');
            $building = $request->input('building');
            $origem = $request->input('origem');
            if($format == 'pdf'){
                (new LeadsExport($report, $items, $dataInicial, $dataFinal, $building, $origem))->store($pathFile);
            } else {
                (new LeadsExport($report, $items, $dataInicial, $dataFinal, $building, $origem))->queue($pathFile);
            }

        } else if( $type === 'buildings' ){
            // Empreendimentos
            if($format == 'pdf'){
                (new BuildingsExport($report, $items))->store($pathFile);
            } else {
                (new BuildingsExport($report, $items))->queue($pathFile);
            }
        
        } else if( $type === 'integrations' ){
            // Integrations
            if($format == 'pdf'){
                (new IntegrationsExport($report, $items))->store($pathFile);
            } else {
                (new IntegrationsExport($report, $items))->queue($pathFile);
            }
        }

        // Salvando log
        UsersLogs::create([
            'title' => 'Geração de relatório',
            'description' => 'Foi realizado a geração do relatório de '. $type .'.',
            'action' => 'reports',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('reports.index')->with('reports', true);
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
        $report = UsersReports::find($id);
        $file = storage_path('app/public/exports/') . $report->name;
        if ( file_exists($file) ) {
            unlink($file);
        }*/

        UsersReports::find($id)->delete();
        return redirect()->route('reports.index')->with('destroy', true);
    }
}