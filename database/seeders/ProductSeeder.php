<?php

namespace Database\Seeders;

use App\Dtos\ProductDto;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ProductDto::fake(
                'https://img.freepik.com/free-photo/elegant-watch-with-silver-golden-chain-isolated_181624-27080.jpg?t=st=1724860385~exp=1724863985~hmac=071ec6985b8341801e8d1cada8ed042a2203010d5a2b5552bcf91713cab58db1&w=740'
            ),
            ProductDto::fake(
                'https://img.freepik.com/premium-photo/cosmetic-products-presentation-mockup-showcase_1277677-4277.jpg?w=740'
            ),
            ProductDto::fake(
                'https://img.freepik.com/free-photo/chicken-skewers-with-slices-sweet-peppers-dill_2829-18813.jpg?t=st=1724859904~exp=1724863504~hmac=28afb5b0ff0857f436a056b0fd051a40d88efc5ddf458458620e09b4d5f559b4&w=740'
            ),
            ProductDto::fake(
                'https://img.freepik.com/free-photo/flat-lay-natural-self-care-products-composition_23-2148990019.jpg?t=st=1724860608~exp=1724864208~hmac=8b1879ffdae135f235e6fa79db335219a278894cda1b3596349f22134658431a&w=740'
            ),
            ProductDto::fake(
                'https://img.freepik.com/free-photo/3d-rendering-personal-care-products-fondant-pink_23-2151053819.jpg?t=st=1724860640~exp=1724864240~hmac=e57f211971f5e53d810582ccc7ef572c8359fee507b73ec71be108c1e178283c&w=740'
            ),
        ];

        collect($products)->map(function ($product) {
            Product::create($product->toArray());
        });
    }
}
