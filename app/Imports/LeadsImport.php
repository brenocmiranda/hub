<?php

namespace App\Imports;

use App\Models\Leads;
use Maatwebsite\Excel\Concerns\ToModel;

class LeadsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   

        return new Leads([
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
