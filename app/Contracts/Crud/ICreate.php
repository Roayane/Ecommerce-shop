<?php

namespace App\Contracts\Crud;


interface ICreate
{
    /**
     * Create a new model instance.
     *
     * @param array $data
     * @return void
     */
    public function create(array $data): void;
}
