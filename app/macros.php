<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;


Builder::macro('getOrFail', function($columns = ['*'])
{
    $models = $this->get($columns);

    if (count($models)) {
        return $models;
    }

    throw (new ModelNotFoundException)->setModel(get_class($this->model));
});