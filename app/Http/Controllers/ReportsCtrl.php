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
        return view('reports.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from reports all
        $reports = UsersReports::orderBy('created_at', 'desc');
        $recordsTotal = UsersReports::count();

        // Search
        $search = $request->search;
        $reports = $reports->where( function($reports) use ($search){
            $reports->orWhere('users_reports.name', 'like', "%".$search."%");
            $reports->orWhere('users_reports.type', 'like', "%".$search."%");
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $reports->count();
        $reports = $reports->skip($skip)->take($pageLength)->get();

        if( $reports->first() ){
            foreach($reports as $report) {
                // Status
                if( $report->status === "Na fila"){
                    $status = '<div class="badge text-bg-secondary">' . $report->status . '</div>';
                }else if( $report->status === "Executando" ) {
                    $status = '<div class="badge text-bg-primary">' . $report->status . '</div>';
                }else if( $report->status === "Gerando" ) {
                    $status = '<div class="badge text-bg-dark">' . $report->status . '</div>';
                }else if( $report->status === "Pronto" ) {
                    $status = '<div class="badge text-bg-success">' . $report->status . '</div>';
                }else {
                    $status = $report->status;
                }

                // Type
                if ($report->type === 'integrations'){
                    $type = 'Integrações';
                } else if($report->type === 'buildings') {
                    $type = 'Empreendimentos';
                } else {
                    $type = 'Leads';
                }

                // Operações
                $url = asset("storage/exports/" . $report->name );
                $download = $report->status === "Pronto" ? '<a href="'. $url .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Download" download><i class="bi bi-download"></i></a>' : "";
                $operations = '<div class="d-flex justify-content-center align-items-center gap-2">'. $download .'<a href="'. route('reports.destroy', $report->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a></div>';
                
                // Array do emp
                $array[] = [
                    'data' => $report->created_at->format("d/m/Y H:i:s"),
                    'name' => $report->name,
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
