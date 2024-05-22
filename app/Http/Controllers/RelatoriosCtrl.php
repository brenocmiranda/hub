<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadsExport;
use App\Exports\BuildingsExport;
use App\Exports\IntegrationsExport;
use App\Models\Buildings;
use App\Models\BuildingsPartners;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\LeadsOrigins;

class RelatoriosCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}

    public function indexLeads()
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

        return view('reports.leads')->with('origins', LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get())->with('array', isset($array) ? $array : null);
    }

    public function generateLeads(Request $request)
    {   
        foreach($request->all() as $index => $element){
            if( $element === "on") {
                $items[] = $index;
            }
        }
        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');
        $format = $request->input('format');
        $building = $request->input('building');
        $origem = $request->input('origem');

        if ($format === 'pdf'){
            return Excel::download(new LeadsExport($items, $dataInicial, $dataFinal, $building, $origem), 'leads-'. date('d-m-Y-H-i-s') .'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }else if($format === 'xls'){
            return Excel::download(new LeadsExport($items, $dataInicial, $dataFinal, $building, $origem), 'leads-'. date('d-m-Y-H-i-s') .'.xls', \Maatwebsite\Excel\Excel::XLS);
        }else if($format === 'csv'){
            return Excel::download(new LeadsExport($items, $dataInicial, $dataFinal, $building, $origem), 'leads-'. date('d-m-Y-H-i-s') .'.csv', \Maatwebsite\Excel\Excel::CSV);
        }else{
            return redirect()->route('reports.leads')->with('reports', false);
        }
    }

    public function indexBuildings()
    {
        return view('reports.buildings');
    }

    public function generateBuildings(Request $request)
    {   
        foreach($request->all() as $index => $element){
            if( $element === "on") {
                $items[] = $index;
            }
        }
        $format = $request->input('format');

        if ($format === 'pdf'){
            return Excel::download(new BuildingsExport($items), 'buildings-'. date('d-m-Y-H-i-s') .'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }else if($format === 'xls'){
            return Excel::download(new BuildingsExport($items), 'buildings-'. date('d-m-Y-H-i-s') .'.xls', \Maatwebsite\Excel\Excel::XLS);
        }else if($format === 'csv'){
            return Excel::download(new BuildingsExport($items), 'buildings-'. date('d-m-Y-H-i-s') .'.csv', \Maatwebsite\Excel\Excel::CSV);
        }else{
            return redirect()->route('reports.buildings')->with('reports', false);
        }
    }

    public function indexIntegrations()
    {
        return view('reports.integrations');
    }

    public function generateIntegrations(Request $request)
    {   
        foreach($request->all() as $index => $element){
            if( $element === "on") {
                $items[] = $index;
            }
        }
        $format = $request->input('format');

        if ($format === 'pdf'){
            return Excel::download(new IntegrationsExport($items), 'integrations-'. date('d-m-Y-H-i-s') .'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }else if($format === 'xls'){
            return Excel::download(new IntegrationsExport($items), 'integrations-'. date('d-m-Y-H-i-s') .'.xls', \Maatwebsite\Excel\Excel::XLS);
        }else if($format === 'csv'){
            return Excel::download(new IntegrationsExport($items), 'integrations-'. date('d-m-Y-H-i-s') .'.csv', \Maatwebsite\Excel\Excel::CSV);
        }else{
            return redirect()->route('reports.integrations')->with('reports', false);
        }
    }
}
