<?php

namespace App\Http\Repository;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{

    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function insert(array $data)
    {

        return  $this->model->insert($data);
    }

    public function select(int $id)
    {
        return  $this->model->find($id);
    }


    public function update(array $data, int $id)
    {
        return  $this->model
            ->where('id', $id)
            ->update($data);
    }
}
