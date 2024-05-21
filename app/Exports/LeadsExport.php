<?php

namespace App\Exports;

use App\Models\Leads;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeadsExport implements FromView
{
    public function __construct($items, $dataInicial, $dataFinal, $building, $origem)  
    { 
        $this->items = $items;
        $this->dataInicial = $dataInicial;
        $this->dataFinal = $dataFinal;
        $this->building = $building;
        $this->origem = $origem;
    }

    public function view(): View
    {   
        $leads = Leads::whereDate('created_at', '>=', $this->dataInicial)->whereDate('created_at', '<=', $this->dataFinal);
        $leads = $this->building ? $leads->where('buildings_id', $this->building) : $leads;
        $leads = $this->origem ? $leads->where('leads_origins_id', $this->origem) : $leads;

        return view('vendor.exports.leads', [
            'leads' => $leads->get(),
            'items' => $this->items,
        ]);
    }
}
