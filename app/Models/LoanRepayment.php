<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model {

    protected $table = "loan_repayment";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

}


