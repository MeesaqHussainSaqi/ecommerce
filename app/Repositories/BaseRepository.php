<?php
namespace App\Repositories;

use App\Repositories\BaseInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseInterface
{
    protected $model;

    // public function __construct(Model $model)
    // {
    //     $this->model = $model;
    // }
    public function __construct()
    {
        $this->model = $this->model();
    }
    abstract protected function model();
    public function all()
    {
        return $this->model->all();
        // return "In the database";
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $model = $this->find($id);
        $model->update($attributes);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        $model->delete();
        return true;
    }
}
