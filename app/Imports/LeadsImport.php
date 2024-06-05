<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Jobs\ProcessBuildingJobs;
use App\Models\Leads;
use App\Models\LeadsFields;
use App\Models\UsersImports;

class LeadsImport implements ToCollection, WithHeadingRow, ShouldQueue, WithEvents, WithChunkReading
{   
    public function __construct($import, $fieldsMandatory, $fieldsOptionalsName, $fieldsOptionalsValue)  
    { 
        $this->import = $import;
        $this->fieldsMandatory = $fieldsMandatory;
        $this->fieldsOptionalsName = $fieldsOptionalsName;
        $this->fieldsOptionalsValue = $fieldsOptionalsValue;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function collection(Collection $rows)
    {   
        foreach ($rows as $row) 
        {   
            // Ajustes de telefone
            $tel = preg_replace( '/\D/', '', str_replace( '+55', '', ltrim($row[$this->fieldsMandatory[2]], "0") ));
            $ddd = substr( $tel, 0, 2 );
            $number = substr( $tel, 2 );
            $number = strlen( $number ) <= 8  ? substr( str_repeat( '9', 9 - strlen( $number ) ) . $number, 0, 9 ) : $number;
            $phone = $ddd . $number;

            $lead = Leads::create([
                'api'                   => 0,
                'name'                  => ucwords(strtolower( $row[$this->fieldsMandatory[0]] )),
                'email'                 => strtolower( $row[$this->fieldsMandatory[1]] ),
                'phone'                 => $phone,
                'batches_id'            => null, 
                'companies_id'          => $this->fieldsMandatory[3],
                'leads_origins_id'      => $this->fieldsMandatory[4],
                'buildings_id'          => $this->fieldsMandatory[5],
            ]);

            if( $this->fieldsOptionalsName ){
                foreach ($this->fieldsOptionalsName as $index => $fieldOptionalsName) { 
                    LeadsFields::create([
                        'name' => $this->fieldsOptionalsName[$index], 
                        'value' => $row[$this->fieldsOptionalsValue[$index]],
                        'leads_id' => $lead->id,
                    ]);
                }
            }

            // Enviando para as execução das integrações
            ProcessBuildingJobs::dispatch($lead->id);
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                UsersImports::find($this->import->id)->update([
                    'status' => 'Executando'
                ]);
            },

            BeforeSheet::class => function(BeforeSheet $event) {
                UsersImports::find($this->import->id)->update([
                    'status' => 'Importando'
                ]);
            },

            AfterSheet::class => function(AfterSheet $event) {
                UsersImports::find($this->import->id)->update([
                    'status' => 'Pronto'
                ]);
            },
        ];
    }
}
