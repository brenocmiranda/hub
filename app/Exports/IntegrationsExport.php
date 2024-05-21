<?php

namespace App\Exports;

use App\Models\Integrations;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IntegrationsExport implements FromView
{
    public function __construct($items)  
    { 
        $this->items = $items;
    }

    public function view(): View
    {   
        return view('vendor.exports.integrations', [
            'integrations' => Integrations::all(),
            'items' => $this->items,
        ]);
    }
}
