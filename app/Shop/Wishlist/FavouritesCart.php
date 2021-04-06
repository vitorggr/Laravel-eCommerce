<?php

namespace App\Shop\Wishlist;

use Gloudemans\Shoppingcart\Cart;

class FavouritesCart extends FavCart
{
    public static $defaultCurrency;

    protected $session;

    protected $event;

    public function __construct()
    {   
        $this->session = $this->getSession();
        $this->event = $this->getEvents();
        parent::__construct($this->session, $this->event);

        self::$defaultCurrency = config('fav.currency');
    }

    public function getSession()
    {
        return app()->make('session');
    }

    public function getEvents()
    {
        return app()->make('events');
    }

    /**
     * Get the total price of the items in the cart.
     *
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     * @param float $shipping
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeparator = null, $shipping = 0.00)
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, FavouriteItem $FavouriteItem) {
            return $total + ($FavouriteItem->qty * $FavouriteItem->priceTax);
        }, 0);

        $grandTotal = $total + $shipping;

        return number_format($grandTotal, $decimals, $decimalPoint, $thousandSeparator);
    }
}
