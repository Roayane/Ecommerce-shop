<?php

namespace App\Contracts\Crud;

/**
 * Interface ICrud used to handle All Crud Operations
 */
interface ICrud extends ICreate, IDelete, IRead, IUpdate
{
}
