<?php

namespace App\Exports\Reports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BankDisbursement implements FromView
{
    public $data;
    function __construct($data)
    {
        $this->data = $data;
    }
    public function view() : View
    {
        return view('pages.reports.exports.bank_disbursement', ['data' =>  $this->data]);
    }
}