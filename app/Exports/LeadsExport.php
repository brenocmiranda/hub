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
use App\Models\UsersReports;
use App\Models\Leads;

class LeadsExport implements FromView, WithEvents
{
    use RegistersEventListeners, Exportable;

    public function __construct($report, $items, $dataInicial, $dataFinal, $building, $origem)  
    { 
        $this->items = $items;
        $this->dataInicial = $dataInicial;
        $this->dataFinal = $dataFinal;
        $this->building = $building;
        $this->origem = $origem;
        $this->report = $report;
    }

    public function view(): View
    {   
        $leads = Leads::whereDate('created_at', '>=', $this->dataInicial)->whereDate('created_at', '<=', $this->dataFinal);
        $leads = $this->building ? $leads->where('buildings_id', $this->building) : $leads;
        $leads = $this->origem ? $leads->where('leads_origins_id', $this->origem) : $leads;

        if( Gate::check('access_komuh') ) {
            return view('vendor.exports.leads', [
                'leads' => $leads->get(),
                'items' => $this->items,
            ]);
        } else {
            return view('vendor.exports.leads', [
                'leads' => $leads->where('companies_id', Auth::user()->companies_id)->get(),
                'items' => $this->items,
            ]);
        }  
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                UsersReports::find($this->report->id)->update([
                    'status' => 'Executando'
                ]);
            },

            BeforeSheet::class => function(BeforeSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                UsersReports::find($this->report->id)->update([
                    'status' => 'Gerando'
                ]);
            },

            AfterSheet::class => function(AfterSheet $event) {
                UsersReports::find($this->report->id)->update([
                    'status' => 'Pronto'
                ]);
            },
        ];
    }
}
