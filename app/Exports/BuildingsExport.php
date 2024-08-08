<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Reports;
use App\Models\Buildings;

class BuildingsExport implements FromView, WithEvents
{
    use RegistersEventListeners, Exportable;
    
    public function __construct($report, $items)  
    { 
        $this->items = $items;
        $this->report = $report;
    }

    public function view(): View
    {   
        if( Gate::check('access_komuh') ) {
            return view('vendor.exports.buildings', [
                'buildings' => Buildings::all(),
                'items' => $this->items,
            ]);
        } else {
            return view('vendor.exports.buildings', [
                'buildings' => Buildings::join('buildings_partners', 'buildings_partners.buildings_id', 'buildings.id')->where('buildings_partners.main', 1)->where('buildings_partners.companies_id', Auth::user()->companies_id)->get(),
                'items' => $this->items,
            ]);
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                Reports::find($this->report->id)->update([
                    'status' => 'Executando'
                ]);
            },

            BeforeSheet::class => function(BeforeSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                Reports::find($this->report->id)->update([
                    'status' => 'Gerando'
                ]);
            },

            AfterSheet::class => function(AfterSheet $event) {
                Reports::find($this->report->id)->update([
                    'status' => 'Pronto'
                ]);
            },
        ];
    }
}
