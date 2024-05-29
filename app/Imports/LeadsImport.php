<?php

namespace App\Imports;

use App\Models\Leads;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

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
}
