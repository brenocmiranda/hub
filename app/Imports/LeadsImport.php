<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Leads;

class LeadsImport implements ToCollection
{
    public function collection(Collection $row)
    {   
        foreach ($rows as $row) 
        {
            User::create([
                'api'                   => 0,
                'name'                  => $row[0],
                'phone'                 => $row[1],
                'email'                 => $row[2], 
                'batches_id'            => null, 
                'companies_id'          => $row[4], 
                'leads_origins_id'      => $row[5], 
                'buildings_id'          => $row[6], 
            ]);
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                UsersReports::find($this->report->id)->update([
                    'status' => 'Executando'
                ]);
            },

            BeforeSheet::class => function(BeforeSheet $event) {
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
