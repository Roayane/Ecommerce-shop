<?php

namespace App\Dtos;

class ProductDto
{
    public string $name;
    public string $description;
    public int $price;
    public string $imageUrl;
   
    /**
     * Create a new class instance.
     */
    public function __construct(string $name, string $description, int $price, string $imageUrl)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
    }

    public static function fake($imageUrl = null): ProductDto{
        return new ProductDto(
            fake()->name(),
            fake()->text(),
            fake()->numberBetween(500, 1000),
            $imageUrl ?? fake()->imageUrl(640, 480)
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->imageUrl,
        ];
    }
}
