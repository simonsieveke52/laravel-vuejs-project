<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use App\Repositories\CartRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $cartRepository;

    public function __construct()
    {
        parent::__construct();
        $this->cartRepository = new CartRepository();
    }

    /**
     * @return void
     */
    public function test_it_can_add_item_to_cart()
    {
        $product = factory(Product::class)->create();

        $this->cartRepository->addToCart($product, 1);

        $count = $this->cartRepository->countItems();

        $this->assertTrue($count === 1);
    }

    /**
     * @return void
     */
    public function test_it_can_find_item_in_cart()
    {
        $product = factory(Product::class)->create();

        $this->cartRepository->addToCart($product, 1);

        $item = $this->cartRepository->findItem($product->id);

        $this->assertTrue($item->id === $product->id);
    }

    /**
     * @return void
     */
    public function test_it_can_update_item_in_cart()
    {
        $product = factory(Product::class)->create();

        $this->cartRepository->addToCart($product, 1);

        $item = $this->cartRepository->findItem($product->id);

        $this->assertTrue($item->quantity === 1);

        $this->cartRepository->updateQuantityInCart($product->id, 5);

        $item = $this->cartRepository->findItem($product->id);

        $this->assertTrue($item->quantity === 5);
    }

    /**
     * @return void
     */
    public function test_it_can_remove_item_in_cart()
    {
        $product = factory(Product::class)->create();

        $this->cartRepository->addToCart($product, 1);

        $item = $this->cartRepository->findItem($product->id);

        $this->assertTrue(!is_null($item));

        $this->cartRepository->remove($product->id);

        $count = $this->cartRepository->countItems();

        $this->assertTrue($count === 0);
    }
}
