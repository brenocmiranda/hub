<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Leads;
use App\Models\LeadsFields;

class ImportsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}

    public function indexLeads()
    {
        return view('imports.leads');
    }

    public function generateLeads(Request $request)
    {   
        foreach($request->all() as $index => $element){
            if( $element === "on") {
                $items[] = $index;
            }
        }

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
}
