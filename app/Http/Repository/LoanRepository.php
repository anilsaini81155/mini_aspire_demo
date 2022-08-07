<?php

namespace App\Http\Repository;

use App\Models\LoanRepayment;


class LoanRepaymentRepository  extends BaseRepository{

    public function __construct(LoanRepayment $model) {
        parent::__construct($model);
    }

    public function getDetails($data)
    {
        return  $this->model->where($data)->first();
    }

}
