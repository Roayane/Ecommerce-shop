<?php

namespace App\Contracts\Crud;

interface IDelete
{
    /**
     * Delete the specified resource.
     *
     * @param mixed $identifier
     * @return bool
     */
    public function delete(mixed $identifier): bool;
}