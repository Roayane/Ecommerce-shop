<?php

namespace App\Contracts\Crud;

interface IUpdate
{
    /**
     * Update the specified resource.
     * @param mixed $identifier
     * @param array $data the data to update
     * @return bool
     * */
    public function update($identifier, $data): bool;
}