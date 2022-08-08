<?php

namespace App\Http\Repository;

use App\Models\Loan;


class LoanRepository  extends BaseRepository{

    public function __construct(Loan $model) {
        parent::__construct($model);
    }

    public function getDetails($data)
    {
        return  $this->model->where($data)->first();
    }


    // public function getUserSpecifiLoanDetails(int $loanId){
        
    //     return  $this->model->join('sys_user', 'loan.user_id', '=', 'sys_user.id')
    //         ->select('sys_user.name','sys_user.loan_id','sys_user.contact_no','loan.amount','loan.tenure','loan.emi_amount')
    //         ->where('loan.id', $loanId)
    //         ->get();

    // }

}
