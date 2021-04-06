<?php

namespace App\Shop\Wishlist\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Couriers\Courier;
use App\Shop\Customers\Customer;
use App\Shop\Products\Product;
use App\Shop\Wishlist\FavouriteItem;
use Illuminate\Support\Collection;

interface WishlistRepositoryInterface extends BaseRepositoryInterface
{
    public function addToWishlist(Product $product, int $int, $options = []) : FavouriteItem;

    public function getWishlistItems() : Collection;

    public function removeToWishlist(string $rowId);
    
    public function countItems() : int;

    public function getSubTotal(int $decimals = 2);

    public function getTotal(int $decimals = 2, $shipping = 0.00);

    public function updateQuantityInWishlist(string $rowId, int $quantity) : FavouriteItem;

    public function findItem(string $rowId) : FavouriteItem;

    public function getTax(int $decimals = 2);

    public function getShippingFee(Courier $courier);

    public function clearWishlist();

    public function saveWishlist(Customer $customer, $instance = 'default');

    public function openWishlist(Customer $customer, $instance = 'default');

    public function getWishlistItemsTransformed() : Collection;
}
