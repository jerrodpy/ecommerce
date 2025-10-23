<?php

namespace App\Repositories\Base\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;

trait HasModelTrait
{
    protected string $class;

    private Model $model;

    /**
     * HasModelTrait constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        throw_unless(isset($this->class), new Exception('property_class_must_be_initialized_for_class'));

        $instance = app($this->class);

        if (!$instance instanceof Model) {
            throw new Exception('property_class_must_be_instance_of_model');
        }

        $this->setModel($instance);
    }

    protected function getModel(): Model
    {
        return $this->model;
    }

    private function setModel(Model $model): void
    {
        $this->model = $model;
    }
}
