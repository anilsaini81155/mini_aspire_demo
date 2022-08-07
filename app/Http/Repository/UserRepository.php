<?php

namespace App\Http\Repository;

use App\Models\User;


class UserRepository  extends BaseRepository{

    public function __construct(User $model) {
        parent::__construct($model);
    }

    public function getDetails($data)
    {
        return  $this->model->where($data)->first();
    }

}
