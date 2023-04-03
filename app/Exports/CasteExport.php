<?php

namespace App\Exports;

use App\Models\Master\Caste;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CasteExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Caste::select('name','status','created_at')->get();
    }
    public function headings(): array
    {
        return [
        'Name',
        'Status',
        'Created At',
        ]; 
    }
}
