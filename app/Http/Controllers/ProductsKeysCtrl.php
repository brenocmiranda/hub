<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\ProductsKeysRqt;
use App\Models\Products;
use App\Models\ProductsKeys;
use App\Models\ProductsPartners;
use App\Models\Companies;
use App\Models\UsersLogs;

class ProductsKeysCtrl extends Controller
{
    public function __construct(){
		$this->middleware('auth');
        $this->middleware('can:keys_show', ['only' => ['index', 'data', 'show']]);
        $this->middleware('can:keys_create', ['only' => ['create', 'store']]);
        $this->middleware('can:keys_update', ['only' => ['edit', 'update']]);
        $this->middleware('can:keys_destroy', ['only' => ['destroy']]);
	}
    
    public function index()
    {
        return view('products.keys.index');
    }

    public function data(Request $request)
    {   
        // Page Length
        $pageLength = $request->limit;
        $skip       = $request->offset;

        // Get data from products key all
        if( Gate::check('access_komuh') ) {
            $keys = ProductsKeys::orderBy('products.name', 'asc')
                                ->join('products', 'products_keys.products_id', '=', 'products.id')
                                ->select('products_keys.*', 'products.name as product');
        } else {
            $keys = ProductsKeys::orderBy('products.name', 'asc')
                                ->join('products', 'products_keys.products_id', '=', 'products.id')
                                ->join('products_partners', 'products_keys.products_id', '=', 'products_partners.products_id')
                                ->where('products_partners.main', 1)
                                ->where('products_partners.companies_id', Auth::user()->companies_id)
                                ->select('products_keys.*', 'products.name as product');
        }                        
        $recordsTotal = ProductsKeys::count();

        // Search
        $search = $request->search;
        $keys = $keys->where( function($keys) use ($search){
            $keys->orWhere('products_keys.value', 'like', "%".$search."%");
            $keys->orWhere('products.name', 'like', "%".$search."%");
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
                $operations = '';
                if (Gate::any(['keys_update', 'keys_destroy'])) {
                    $operations .= '<div class="d-flex justify-content-center align-items-center gap-2">';

                    if( Gate::check('keys_update') ) {
                        $operations .= '<a href="'. route('products.keys.edit', $key->id ) .'" class="btn btn-outline-secondary px-2 py-1" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Editar"><i class="bi bi-pencil"></i></a>';
                    }
                    if( Gate::check('keys_destroy') ) {
                        $operations .= '<a href="'. route('products.keys.destroy', $key->id ) .'" class="btn btn-outline-secondary px-2 py-1 destroy" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Excluir"><i class="bi bi-trash"></i></a>';
                    }

                    $operations .= '</div>';
                } else {
                    $operations = '-';
                }
                
                // Array do emp
                $array[] = [
                    'produto' => $key->product,
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
        if( Gate::check('access_komuh') ) {
            $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
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

        return view('products.keys.create')->with('array', isset($array) ? $array : null);
    }

    public function store(ProductsKeysRqt $request)
    {      
        ProductsKeys::create([
            'value' => $request->value, 
            'products_id' => $request->product, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Cadastro de nova chave',
            'description' => 'Foi realizado o cadastro de uma nova chave: ' . $request->value . '.',
            'action' => 'create',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('products.keys.index')->with('create', true);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {      
        if( Gate::check('access_komuh') ) {
            $companies = Companies::where('active', 1)->orderBy('name', 'asc')->get();
        } else {
            $companies = Companies::where('id', Auth::user()->companies_id)->get();
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

        return view('products.keys.edit')->with('key', ProductsKeys::find($id))->with('array', isset($array) ? $array : null);
    } 

    public function update(ProductsKeysRqt $request, string $id)
    {
        ProductsKeys::find($id)->update([
            'value' => $request->value, 
            'products_id' => $request->product, 
            'active' => $request->active,
        ]);

        // Salvando log
        UsersLogs::create([
            'title' => 'Atualização das informações da chave',
            'description' => 'Foi realizado a atualização das informações da chave: ' . $request->value . '.',
            'action' => 'update',
            'users_id' => Auth::user()->id
        ]);

        return redirect()->route('products.keys.index')->with('edit', true);
    }

    public function destroy(string $id)
    {     
        // Salvando log
        UsersLogs::create([
            'title' => 'Exclusão de chave',
            'description' => 'Foi realizado a exclusão da chave: ' .  ProductsKeys::find($id)->value . '.',
            'action' => 'destroy',
            'users_id' => Auth::user()->id
        ]);

        ProductsKeys::find($id)->delete();
        return redirect()->route('products.keys.index')->with('destroy', true);
    }
}
