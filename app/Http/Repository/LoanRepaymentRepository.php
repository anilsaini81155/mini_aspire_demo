<?php

namespace App\Http\Repository;

use App\Models\LoanRepayment;


class LoanRepaymentRepository  extends BaseRepository{

    public function __construct(LoanRepayment $model) {
        parent::__construct($model);
    }

    public function getDetails(array $data)
    {
        return  $this->model->where($data)->first();
    }

    public function getSchedule(int $loan_id)
    {
        return  $this->model->where('loan_id', $loan_id)->get();
    }

}
