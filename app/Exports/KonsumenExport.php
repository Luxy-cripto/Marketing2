<?php

namespace App\Exports;

use App\Models\Konsumen;
use Maatwebsite\Excel\Concerns\FromCollection;

class KonsumenExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Konsumen::all();
    }
}
