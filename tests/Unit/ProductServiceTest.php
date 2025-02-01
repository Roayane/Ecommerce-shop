<?php

namespace Tests\Unit;

use App\Dtos\ProductDto;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;
    private ProductService $productService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productService = App::make(ProductService::class);
    }
     
    public function test_that_getAll_returns_all_products(): void
    {
        $product = ProductDto::fake(
            'https://img.freepik.com/free-photo/elegant-watch-with-silver-golden-chain-isolated_181624-27080.jpg?t=st=1724860385~exp=1724863985~hmac=071ec6985b8341801e8d1cada8ed042a2203010d5a2b5552bcf91713cab58db1&w=740'
        );
        Product::create($product->toArray());

        $products = $this->productService->getAll();
        $this->assertCount(1, $products);
    }

    public function test_that_getById_returns_product(): void{
        //when
        $product1 = ProductDto::fake(
            'https://img.freepik.com/free-photo/elegant-watch-with-silver-golden-chain-isolated_181624-27080.jpg?t=st=1724860385~exp=1724863985~hmac=071ec6985b8341801e8d1cada8ed042a2203010d5a2b5552bcf91713cab58db1&w=740'
        );

        $product2 = ProductDto::fake(
            'https://img.freepik.com/premium-photo/cosmetic-products-presentation-mockup-showcase_1277677-4277.jpg?w=740'
        );

        //then
        $product1Created = Product::create($product1->toArray());
        $foundedProduct = $this->productService->getById($product1Created->id);

        //assert
        $this->assertEquals($product1->name, $foundedProduct->name);
        $this->assertNotEquals($product2->name, $foundedProduct->name);
    }
}
