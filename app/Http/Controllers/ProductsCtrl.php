<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\ProductsRqt;
use App\Models\Products;
use App\Models\ProductsPartners;
use App\Models\ProductsDestinatarios;
use App\Models\ProductsSheets;
use App\Models\ProductsIntegrations;
use App\Models\ProductsIntegrationsFields;
use App\Models\ProductsKeys;
use App\Models\Companies;
use App\Models\Integrations;
use App\Models\UsersLogs;

class ProductsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:products_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:products_create', ['only' => ['create', 'store']]);
        $this->middleware('can:products_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:products_destroy', ['only' => ['destroy']]);
        $this->middleware('can:products_duplicate', ['only' => ['duplicate']]);
	}
    
    public function index()
    {
        return view('products.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from products all
        if( Gate::check('access_komuh') ) {
            $products = Products::orderBy('companies.name', 'asc')->orderBy('products.name', 'asc')
                                ->join('products_partners', 'products_partners.products_id', '=', 'products.id')
                                ->join('companies', 'products_partners.companies_id', '=', 'companies.id')
                                ->where('products_partners.main', 1)
                                ->select('products.*', 'companies.name as company');
        } else {
            $products = Products::orderBy('products.name', 'asc')
                                ->join('products_partners', 'products_partners.products_id', '=', 'products.id')
                                ->where('products_partners.main', 1)
                                ->where('products_partners.companies_id', Auth::user()->companies_id)
                                ->select('products.*');
        }
        $recordsTotal = Products::count();

        // Search
        $search = $request->search;
        $products = $products->where( function($products) use ($search){
            $products->orWhere('products.name', 'like', "%".$search."%");
            if( Gate::check('access_komuh') ) {
                $products->orWhere('companies.name', 'like', "%".$search."%");
            }
        });

        // Apply Length and Capture RecordsFilters
        $recordsFiltered = $recordsTotal = $products->count();
        $products = $products->skip($skip)->take($pageLength)->get();

        if( $products->first() ){
            foreach($products as $product) {
                // Status
                if( $product->active ) {
                    $status = '<span class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">Ativo</span>';
                } else { 
                    $status = '<span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">Desativado</span>';
                } 
            
                // Operações
                $operations = '';
                if (Gate::any(['products_update', 'products_duplicate', 'products_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('products_update') ) {
                        $operations .= '<a href="' . route('products.edit', $product->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>';
                    }
                    if( Gate::check('products_duplicate') ) {
                        $operations .= '<a href="'. route('products.duplicate', $product->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Duplicar"><i class="bi bi-copy"></i></a>';
                    }
                    if( Gate::check('products_destroy') ) {
                        $operations .= '<a href="'. route('products.destroy', $product->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }
                
                // Array do emp
                $array[] = [
                    'name' => $product->name,
                    'company' => Gate::check('access_komuh') ? $product->company : '-',
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
            $integrations = Integrations::where('active', 1)->orderBy('name', 'asc')->get();
            $products = Products::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
            $integrations = Integrations::where('active', 1)->where('companies_id', Auth::user()->companies_id)->orderBy('name', 'asc')->get();
            $products = Products::join('products_partners', 'products_partners.products_id', 'products.id')->where('products_partners.companies_id', Auth::user()->companies_id)->where('products_partners.main', 1)->where('active', 1)->orderBy('name', 'asc')->get();
        }
        
        // Integrations
        foreach($companies as $company){
            foreach($integrations as $integration){ 
                if( $company->id == $integration->companies_id ){
                    $arrayIntegration[$company->name][] = $integration;
                }
            }
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
                    $arrayProduct[$company->name][] = $product;
                }
            }
        } 

        return view('products.create')->with('companies', Companies::where('active', 1)->orderBy('name', 'asc')->get())->with('integrations', isset($arrayIntegration) ? $arrayIntegration : null)->with('productsAll', isset($arrayProduct) ? $arrayProduct : null);
    }

    public function store(ProductsRqt $request)
    {    
        // Cadastrando novo produto
        $product = Products::create([
            'name' => $request->name, 
            'active' => $request->active,
            'products_id' => $request->products_id ? $request->products_id : null,
        ]);

        // Cadastrando novos parceiros
        $partners = $request->input('partner');
        if(isset($partners)){
            foreach($partners as $index => $partner) {
                $productsPartners = ProductsPartners::create([
                    'main' => $request->input('main')[$index], 
                    'leads' => $request->input('leads')[$index], 
                    'companies_id' => $request->input('partner')[$index], 
                    'products_id' => $product->id, 
                ]);
            }
        }

        // Cadastrando novos destinatários
        $destinatarios = $request->input('email');
        if(isset($destinatarios)){
            foreach($destinatarios as $destinatario) {
                $productDestinatario = ProductsDestinatarios::create([
                    'email' => $destinatario,
                    'products_id' => $product->id, 
                ]);
            }
        }

        // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $index1 => $sheet) {
                if($request->hasFile('file.'.$index1)){
                    // Upload file
                    $filenameWithExt = $request->file('file')[$index1]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$index1]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$index1]->storeAs('public/google', $fileNameToStore);
                }
                $productSheet = ProductsSheets::create([
                    'sheet' => $request->input('sheet')[$index1],
                    'spreadsheetID' => $request->input('spreadsheetID')[$index1],
                    'file' => $fileNameToStore,
                    'products_id' => $product->id, 
                ]);
            }
        }

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        if(isset($integrations)){
            foreach($integrations as $integration) {
                $productIntegration = ProductsIntegrations::create([
                    'products_id' => $product->id, 
                    'integrations_id' => $integration['nameIntegration'],
                ]);
                if( isset($integration['nameField']) ) {
                    foreach($integration['nameField'] as $index2 => $field) {
                        ProductsIntegrationsFields::create([
                            'name' => $integration['nameField'][$index2], 
                            'value' => $integration['valueField'][$index2],
                            'products_id' => $product->id,
                            'integrations_id' => $integration['nameIntegration'],
                        ]);
                    }
                }
            }
        }
        
        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de novo produto',
            'description' => 'Foi realizado o cadastro de um novo produto: ' . $request->name . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('products.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {   
        if( Gate::check('access_komuh') ) { 
            $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
            $integrations = Integrations::where('active', 1)->orderBy('name', 'asc')->get();
            $products = Products::where('active', 1)->orderBy('name', 'asc')->get();
            $companiesAll = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
            $integrations = Integrations::where('active', 1)->where('companies_id', Auth::user()->companies_id)->orderBy('name', 'asc')->get();
            $products = Products::join('products_partners', 'products_partners.products_id', 'products.id')->where('products_partners.main', 1)->where('products_partners.companies_id', Auth::user()->companies_id)->where('active', 1)->orderBy('name', 'asc')->get();
            $companiesAll = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        }
        
        // Integrations
        foreach($companies as $company){
            foreach($integrations as $integration){ 
                if( $company->id == $integration->companies_id ){
                    $arrayIntegration[$company->name][] = $integration;
                }
            }
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
                    $arrayProduct[$company->name][] = $product;
                }
            }
        } 

        return view('products.edit')->with('product', Products::find($id))->with('companies', $companiesAll)->with('integrations', isset($arrayIntegration) ? $arrayIntegration : null)->with('productsAll', isset($arrayProduct) ? $arrayProduct : null);
    }

    public function update(ProductsRqt $request, $id)
    {
        // Removendo os registros anteriores
        ProductsPartners::where('products_id', $id)->forceDelete();
        ProductsDestinatarios::where('products_id', $id)->forceDelete();
        ProductsSheets::where('products_id', $id)->forceDelete();
        ProductsIntegrationsFields::where('products_id', $id)->forceDelete();
        ProductsIntegrations::where('products_id', $id)->forceDelete();

        // Atualizando os dados do produto
        Products::find($id)->update([
            'name' => $request->name, 
            'active' => $request->active, 
            'products_id' => $request->products_id ? $request->products_id : null,
        ]);

        // Cadastrando novos parceiros
        $partners = $request->input('partner');
        if(isset($partners)){
            foreach($partners as $index => $partner) {
                $productsPartners = ProductsPartners::create([
                    'main' => $request->input('main')[$index], 
                    'leads' => $request->input('leads')[$index], 
                    'companies_id' => $request->input('partner')[$index], 
                    'products_id' => $id, 
                ]);
            }
        }

        // Cadastrando novos destinatários
        $destinatarios = $request->input('email');
        if(isset($destinatarios)){
            foreach($destinatarios as $destinatario) {
                $productDestinatario = ProductsDestinatarios::create([
                    'email' => $destinatario,
                    'products_id' => $id, 
                ]);
            }
        }

        // Cadastrando novos sheets
        $sheets = $request->input('spreadsheetID');
        if(isset($sheets)){
            foreach($sheets as $index1 => $sheet) {
                if($request->hasFile('file.'.$index1)){
                    // Upload file
                    $filenameWithExt = $request->file('file')[$index1]->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('file')[$index1]->getClientOriginalExtension();
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    $path = $request->file('file')[$index1]->storeAs('public/google', $fileNameToStore);

                    $productSheet = ProductsSheets::create([
                        'sheet' => $request->input('sheet')[$index1],
                        'spreadsheetID' => $request->input('spreadsheetID')[$index1],
                        'file' => $fileNameToStore,
                        'products_id' => $id,
                    ]);
                } else {
                    $productSheet = ProductsSheets::create([
                        'sheet' => $request->input('sheet')[$index1],
                        'spreadsheetID' => $request->input('spreadsheetID')[$index1],
                        'file' => $request->input('fileexists')[$index1],
                        'products_id' => $id,
                    ]);
                }
            }
        }

        // Cadastrando novas integrações e novos campos
        $integrations = $request->input('array');
        if(isset($integrations)){
            foreach($integrations as $integration) {
                $productIntegration = ProductsIntegrations::create([
                    'products_id' => $id, 
                    'integrations_id' => $integration['nameIntegration'],
                ]);
                if( isset($integration['nameField'][0]) ){
                    foreach($integration['nameField'] as $index2 => $field) {
                        ProductsIntegrationsFields::create([
                            'name' => $integration['nameField'][$index2], 
                            'value' => $integration['valueField'][$index2],
                            'products_id' => $id,
                            'integrations_id' => $integration['nameIntegration'],
                        ]);
                    }
                }
            }
        }

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações do produto',
            'description' => 'Foi realizado a atualização das informações do produto: ' . $request->name . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('products.index')->with('edit', true);
    }

    public function destroy(string $id)
    {   
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de produto',
            'description' => 'Foi realizado a exclusão do produto: ' . Products::find($id)->name . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);
        
        ProductsPartners::where('products_id', $id)->delete();
        ProductsDestinatarios::where('products_id', $id)->delete();
        ProductsSheets::where('products_id', $id)->delete();
        ProductsIntegrationsFields::where('products_id', $id)->delete();
        ProductsIntegrations::where('products_id', $id)->delete();
        ProductsKeys::where('products_id', $id)->delete();
        Products::find($id)->delete();
        return redirect()->route('products.index')->with('destroy', true);
    }

    public function duplicate(string $id)
    {
        $product = Products::find($id);
        $productsPartners = ProductsPartners::where('products_id', $id)->get();
        $productIntegrations = ProductsIntegrations::where('products_id', $id)->get();
        $productDestinatarios = ProductsDestinatarios::where('products_id', $id)->get();
        $productSheets = ProductsSheets::where('products_id', $id)->get();
        $productIntegrationsFields = ProductsIntegrationsFields::where('products_id', $id)->get();

        // Cadastrando novo produto
        $productNew = Products::create([
            'name' =>  'Copy_' . $product->name, 
            'active' => $product->active,
            'products_id' => $product->products_id,
        ]);
        
        // Cadastrando novos parceiros
        if(isset($productsPartners)){
            foreach($productsPartners as $index => $partner) {
                $productsPartner = ProductsPartners::create([
                    'main' => $partner->main, 
                    'leads' => $partner->leads, 
                    'companies_id' => $partner->companies_id, 
                    'products_id' => $productNew->id, 
                ]);
            }
        }

        // Cadastrando novos destinatários
        if(isset($productDestinatarios)){
            foreach($productDestinatarios as $destinatario) {
                $productDestinatario = ProductsDestinatarios::create([
                    'email' => $destinatario->email,
                    'products_id' => $productNew->id, 
                ]);
            }
        }

        // Cadastrando novos sheets
        if(isset($productSheets)){
            foreach($productSheets as $in => $sheet) {
                $productSheet = ProductsSheets::create([
                    'sheet' => $sheet->sheet,
                    'spreadsheetID' => $sheet->spreadsheetID,
                    'file' => $sheet->file,
                    'products_id' => $productNew->id,
                ]);
            }
        }

        // Cadastrando novas integrações e novos campos
        if(isset($productIntegrations)){
            foreach($productIntegrations as $integration) {
                $productIntegration = ProductsIntegrations::create([
                    'products_id' => $productNew->id, 
                    'integrations_id' => $integration->integrations_id,
                ]);

                foreach($productIntegrationsFields as $index => $field) {
                    if($field->integrations_id == $integration->integrations_id) {
                        ProductsIntegrationsFields::create([
                            'name' => $field->name, 
                            'value' => $field->value,
                            'products_id' => $productNew->id,
                            'integrations_id' => $integration->integrations_id,
                        ]);
                    } 
                }
            }
        }

        // Salvando log
        UsersLogs::create([
            'title' => 'Duplicação do produto',
            'description' => 'Foi realizada o duplicação do produto ' . $product->name . ' com o nome ' . $productNew->name . '.',
            'action' => 'duplicate',
            'users_id' => Auth::user()->id
        ]);
        
        return redirect()->route('products.index')->with('duplicate', true);
    }
}
