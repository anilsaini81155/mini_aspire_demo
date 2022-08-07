<?php

namespace App\Http\Repository;

use App\Models\SysConfig;


class SysConfigRepository  extends BaseRepository{

    public function __construct(SysConfig $model) {
        parent::__construct($model);
    }

    public function getSysDetails($data)
    {
        return  $this->model->where($data)->first();
    }

}
