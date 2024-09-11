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
    
    public function __construct($report, $items, $company)  
    { 
        $this->items = $items;
        $this->report = $report;
        $this->company = $company;
    }

    public function view(): View
    {   
        $buildings = Buildings::all();

        if( !Gate::check('access_komuh') ) {
            $buildings->join('buildings_partners', 'buildings_partners.buildings_id', 'buildings.id')->where('buildings_partners.main', 1)->where('buildings_partners.companies_id', $this->company)->get();
        }    

        return view('vendor.exports.buildings', [
            'buildings' => $buildings,
            'items' => $this->items,
        ]);
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
