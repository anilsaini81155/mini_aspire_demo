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

}
