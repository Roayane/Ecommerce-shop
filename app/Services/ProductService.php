<?php

namespace App\Services;
use App\Contracts\Crud\IRead;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
class ProductService implements IRead
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll(): array
    {
        return Product::query()
                        ->get()
                        ->toArray();
    }

    public function getById($identifier): Model
    {
        return Product::findOrFail($identifier);
    }
}
