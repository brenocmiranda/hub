<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadsExport;
use App\Exports\ProductsExport;
use App\Exports\IntegrationsExport;
use App\Models\Products;
use App\Models\ProductsPartners;
use App\Models\Companies;
use App\Models\LeadsOrigins;
use App\Models\UsersLogs;
use App\Models\Reports;

class ReportsCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:reports_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:reports_create', ['only' => ['create', 'store']]);
        $this->middleware('can:reports_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:reports_destroy', ['only' => ['destroy']]);
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
        if( Gate::check('access_komuh') ) {
            $reports = Reports::orderBy('reports.created_at', 'desc')
                                ->join('companies', 'reports.companies_id', '=', 'companies.id')
                                ->select('reports.*', 'companies.name as company');
        } else {
            $reports = Reports::orderBy('reports.created_at', 'desc')
                                ->where('companies_id', Auth::user()->companies_id);
        }  
        $recordsTotal = Reports::count();

        // Search
        $search = $request->search;
        $reports = $reports->where( function($reports) use ($search){
            $reports->orWhere('reports.name', 'like', "%".$search."%");
            $reports->orWhere('reports.type', 'like', "%".$search."%");

            if( Gate::check('access_komuh') ) {
                $reports->orWhere('companies.name', 'like', "%".$search."%");
            }
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
                } else if($report->type === 'products') {
                    $type = 'Produtos';
                } else {
                    $type = 'Leads';
                }

                // Operações
                $operations = '';
                if (Gate::any(['reports_show', 'reports_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('reports_show') && $report->status === "Pronto" ) {
                        $url = asset("storage/exports/" . $report->name );
                        $operations .= '<a href="'. $url .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Download" download><i class="bi bi-download"></i></a>';
                    }
                    if( Gate::check('reports_destroy') ) {
                        $operations .= '<a href="'. route('reports.destroy', $report->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }

                // Array do emp
                $array[] = [
                    'data' => $report->created_at->format("d/m/Y H:i:s"),
                    'name' => $report->name,
                    'type' => $type,
                    'company' => Gate::check('access_komuh') ? $report->company : '-',
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
        if( Gate::check('access_komuh') ) {
            $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
            $origins = LeadsOrigins::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
            $origins = LeadsOrigins::where('companies_id', Auth::user()->companies_id)->where('active', 1)->orderBy('name', 'asc')->get();
        }

        // Products
        $products = Products::where('active', 1)->orderBy('name', 'asc')->get();
        foreach($products as $product){
            $element = ProductsPartners::where('products_id', $product->id)->where('main', 1)->first();
            $product->companies_id = $element ? $element->companies_id : 0;
        }
        foreach($companies as $company){
            foreach($products as $product){ 
                if( $company->id == $product->companies_id ){
                    $array[$company->name][] = $product;
                }
            }
        } 

        // Origins
        foreach($companies as $company){
            foreach($origins as $origin){ 
                if( $company->id == $origin->companies_id ){
                    $array1[$company->name][] = $origin;
                }
            }
        } 

        return view('reports.create')->with('origins', isset($array1) ? $array1 : null)->with('products', isset($array) ? $array : null);
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
        $company = Auth::user()->companies_id;

        // Create data in reports
        $report = Reports::create([
            'name' => $nameFile, 
            'type' => $type, 
            'status' => 'Na fila',
            'companies_id' => Auth::user()->companies_id,
        ]);

        if( $type === 'leads' ){
            // Leads 
            $dataInicial = $request->input('dataInicial');
            $dataFinal = $request->input('dataFinal');
            $product = $request->input('product');
            $origem = $request->input('origem');

            if($format == 'pdf'){
                (new LeadsExport($report, $items, $dataInicial, $dataFinal, $product, $origem, $company))->store($pathFile);
            } else {
                (new LeadsExport($report, $items, $dataInicial, $dataFinal, $product, $origem, $company))->queue($pathFile);
            }

        } else if( $type === 'products' ){
            // Produtos
            if($format == 'pdf'){
                (new ProductsExport($report, $items, $company))->store($pathFile);
            } else {
                (new ProductsExport($report, $items, $company))->queue($pathFile);
            }
        
        } else if( $type === 'integrations' ){
            // Integrations
            if($format == 'pdf'){
                (new IntegrationsExport($report, $items, $company))->store($pathFile);
            } else {
                (new IntegrationsExport($report, $items, $company))->queue($pathFile);
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
        $report = Reports::find($id);
        $file = storage_path('app/public/exports/') . $report->name;
        if ( file_exists($file) ) {
            unlink($file);
        }*/

        Reports::find($id)->delete();
        return redirect()->route('reports.index')->with('destroy', true);
    }
}
