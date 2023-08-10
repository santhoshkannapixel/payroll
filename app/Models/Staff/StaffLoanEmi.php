<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLoanEmi extends Model
{
    use HasFactory;

    protected $table = 'staff_loan_emi';

    protected $fillable = [
        'staff_id',
        'staff_loan_id',
        'emi_date',
        'emi_month',
        'amount',
        'loan_mode',
        'loan_type',
        'status'
    ];

}
