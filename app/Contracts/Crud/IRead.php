<?php

namespace App\Contracts\Crud;

use Illuminate\Database\Eloquent\Model;

interface IRead
{
    /**
     * Get all of the resource.
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Get the specified resource.
     *
     * @param mixed $identifier
     * @return Model
     */
    public function getById($identifier): Model;
}