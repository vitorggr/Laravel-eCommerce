<?php

namespace App\Shop\Wishlist\Repositories;

use App\Shop\Carts\ShoppingCart;
use App\Shop\Wishlist\FavouritesCart;
use App\Shop\Wishlist\Repositories\Interfaces\WishlistRepositoryInterface;
use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Wishlist\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Couriers\Courier;
use App\Shop\Customers\Customer;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Wishlist\FavouriteItem;
use Exception;
use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;
use Illuminate\Support\Collection;

class WishlistRepository extends BaseRepository implements WishlistRepositoryInterface
{
    /**
     * CartRepository constructor.
     * @param FavouritesCart $cart
     */
    public function __construct(FavouritesCart $cart)
    {
        $this->model = $cart;
    }

    /**
     * @param Product $product
     * @param int $int
     * @param array $options
     * @return FavouriteItem
     */
    public function addToWishlist(Product $product, int $int, $options = []) : FavouriteItem
    {   
        return $this->model->add($product, $int, $options);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getWishlistItems() : Collection
    {
        return $this->model->content();
    }

    /**
     * @param string $rowId
     *
     * @throws ProductInCartNotFoundException
     */
    public function removeToWishlist(string $rowId)
    {
        try {
            $this->model->remove($rowId);
        } catch (InvalidRowIDException $e) {
            throw new Exception('Product in cart not found.');
        }
    }

    /**
     * Count the items in the cart
     *
     * @return int
     */
    public function countItems() : int
    {
        return $this->model->count();
    }

    /**
     * Get the sub total of all the items in the cart
     *
     * @param int $decimals
     * @return float
     */
    public function getSubTotal(int $decimals = 2)
    {
        return $this->model->subtotal($decimals, '.', '');
    }

    /**
     * Get the final total of all the items in the cart minus tax
     *
     * @param int $decimals
     * @param float $shipping
     * @return float
     */
    public function getTotal(int $decimals = 2, $shipping = 0.00)
    {
        return $this->model->total($decimals, '.', '', $shipping);
    }
   

    /**
     * Return the specific item in the cart
     *
     * @param string $rowId
     * @return \Gloudemans\Shoppingcart\FavouriteItem
     */
    public function findItem(string $rowId) : FavouriteItem
    {
        return $this->model->get($rowId);
    }

    /**
     * Returns the tax
     *
     * @param int $decimals
     * @return float
     */
    public function getTax(int $decimals = 2)
    {
        return $this->model->tax($decimals);
    }

    /**
     * @param Courier $courier
     * @return mixed
     */
    public function getShippingFee(Courier $courier)
    {
        return number_format($courier->cost, 2);
    }

    /**
     * Clear the cart content
     */
    public function clearWishlist()
    {
        $this->model->destroy();
    }

    /**
     * @param Customer $customer
     * @param string $instance
     */
    public function saveWishlist(Customer $customer, $instance = 'default')
    {
        $this->model->instance($instance)->store($customer->email);
    }

    /**
     * @param Customer $customer
     * @param string $instance
     * @return Cart
     */
    public function openWishlist(Customer $customer, $instance = 'default')
    {
        $this->model->instance($instance)->restore($customer->email);
        return $this->model;
    }

    /**
     * @return Collection
     */
    public function getWishlistItemsTransformed() : Collection
    {
        return $this->getWishlistItems()->map(function ($item) {
            $productRepo = new ProductRepository(new Product());
            $product = $productRepo->findProductById($item->id);
            $item->product = $product;
            $item->cover = $product->cover;
            $item->description = $product->description;
            return $item;
        });
    }

    public function updateQuantityInWishlist(string $rowId, int $quantity): FavouriteItem
    {
        return $this->model->update($rowId, $quantity);
    }
    
}
