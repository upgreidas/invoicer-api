<?php

namespace App\Services;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected $model;

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function table()
    {
        return $this->model->getTable();
    }

    public function find($resourceID)
    {
      return $this->model->findOrFail($resourceID);
    }

    public function paginate($itemsPerPage = 20)
    {
      return $this->model->paginate();
    }

    public function create($data)
    {
      return $this->model->create($data)->fresh();
    }

    public function update($resourceID, $data)
    {
      $resource = $this->find($resourceID);

      $resource->update($data);

      return $resource;
    }

    public function delete($resourceID)
    {
      $resource = $this->find($resourceID);

      return $resource->delete();
    }
}
