<?php

namespace App\Http\Repository;

use App\Models\User;


class UserRepository  extends BaseRepository{

    public function __construct(User $model) {
        parent::__construct($model);
    }

    public function getDetails(array $data)
    {
        return  $this->model->where($data)->first();
    }

    public function getUserSpecifiLoanDetails(int $loanId){

        return  $this->model
            ->select('sys_user.name', 'sys_user.loan_id','sys_user.contact_no','loan.amount','loan.tenure','loan.emi_amount')
            ->join('loan', 'loan.user_id', '=', 'sys_user.id')
            ->where('loan.id', $loanId)
            ->first();

    }

}
