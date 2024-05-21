<?php

namespace App\Exports;

use App\Models\Buildings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BuildingsExport implements FromView
{
    public function __construct($items)  
    { 
        $this->items = $items;
    }

    public function view(): View
    {   
        return view('vendor.exports.buildings', [
            'buildings' => Buildings::all(),
            'items' => $this->items,
        ]);
    }
}
