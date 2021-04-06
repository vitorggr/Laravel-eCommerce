<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::namespace('Admin')->group(function () {
    Route::get('admin/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('admin/login', 'LoginController@login')->name('admin.login');
    Route::get('admin/logout', 'LoginController@logout')->name('admin.logout');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::namespace('Admin')->group(function () {
        Route::group([], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::namespace('Collections')->group(function () {
                Route::resource('collections', 'CollectionController');
            });
            Route::namespace('Coupons')->group(function () {
                Route::resource('coupons', 'CupomController');
            });
            Route::namespace('Promotions')->group(function () {
                Route::resource('promotions', 'PromotionController');
            });
            Route::namespace('Products')->group(function () {
                Route::resource('products', 'ProductController');
                Route::get('remove-image-product', 'ProductController@removeImage')->name('product.remove.image');
                Route::get('remove-image-thumb', 'ProductController@removeThumbnail')->name('product.remove.thumb');
            });
            Route::namespace('Customers')->group(function () {
                Route::resource('customers', 'CustomerController');
                Route::resource('customers.addresses', 'CustomerAddressController');
            });
            Route::namespace('Categories')->group(function () {
                Route::resource('categories', 'CategoryController');
                Route::get('remove-image-category', 'CategoryController@removeImage')->name('category.remove.image');
            });
            Route::namespace('Orders')->group(function () {
                Route::resource('orders', 'OrderController');
                Route::resource('order-statuses', 'OrderStatusController');
                Route::get('orders/{id}/invoice', 'OrderController@generateInvoice')->name('orders.invoice.generate');
            });
            Route::resource('addresses', 'Addresses\AddressController');
            Route::resource('countries', 'Countries\CountryController');
            Route::resource('countries.provinces', 'Provinces\ProvinceController');
            Route::resource('countries.provinces.cities', 'Cities\CityController');
            Route::resource('attributes', 'Attributes\AttributeController');
            Route::resource('attributes.values', 'Attributes\AttributeValueController');
            Route::resource('brands', 'Brands\BrandController');
        });
        Route::group([], function () {
            Route::resource('employees', 'EmployeeController');
            Route::get('employees/{id}/profile', 'EmployeeController@getProfile')->name('employee.profile');
            Route::put('employees/{id}/profile', 'EmployeeController@updateProfile')->name('employee.profile.update');
            Route::resource('roles', 'Roles\RoleController');
            Route::resource('permissions', 'Permissions\PermissionController');
        });
    });
});

/**
 * Frontend routes
 */
Auth::routes();

Route::namespace('Auth')->group(function () {
    Route::get('cart/login', 'CartLoginController@showLoginForm')->name('cart.login');
    Route::post('cart/login', 'CartLoginController@login')->name('cart.login');
    Route::get('logout', 'LoginController@logout');
});

Route::namespace('Front')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('contato', 'HomeController@getContact');
    Route::post('contato', 'HomeController@storeContact');
    Route::post('/', 'NewsletterController@store');

    Route::group(['middleware' => ['auth', 'web']], function () {

        Route::namespace('Payments')->group(function () {
            Route::get('bank-transfer', 'BankTransferController@index')->name('bank-transfer.index');
            Route::post('bank-transfer', 'BankTransferController@store')->name('bank-transfer.store');
        });

        Route::namespace('Addresses')->group(function () {
            Route::resource('country.state', 'CountryStateController');
            Route::resource('state.city', 'StateCityController');
        });

        Route::get('conta', 'AccountsController@index')->name('conta');
        Route::post('conta', 'AccountsController@update')->name('accounts.update');
        // parei em checkout ->
        Route::get('checkout', 'CheckoutController@index')->name('checkout.index');
        Route::get('pedido', 'CheckoutController@payment')->name('pedido.index');
        //Route::get('checkout?cupom={cupom}', 'CheckoutController@index')->name('checkout.index');
        Route::post('checkout/frete', 'CheckoutController@getFrete')->name('checkout.shipping');
        // Route::get('checkout/pagamento', 'CheckoutController@payment')->name('checkout.payment');
        Route::post('checkout/boleto', 'CheckoutController@executeBoletoPayment')->name('checkout.boleto');
        Route::post('checkout/pix', 'CheckoutController@executePixPayment')->name('checkout.pix');
        Route::post('checkout/execute', 'CheckoutController@charge')->name('checkout.execute');
        Route::get('checkout/cancel', 'CheckoutController@cancel')->name('checkout.cancel');
        Route::get('checkout/sucesso', 'CheckoutController@success')->name('checkout.success');
        Route::post('checkout/storeaddress', 'CheckoutController@storeAddress')->name('checkout.storeaddress');
        Route::resource('customer.address', 'CustomerAddressController');
        Route::resource('checkout', 'CheckoutController');
        Route::get('cep', 'CheckoutController@getCep')->name('cep');
        Route::get('busca', 'CheckoutController@calculaFrete');
    });

    Route::resource('carrinho', 'CartController');
    Route::get('carrinho/mais/{rowId}/{qty}', 'CartController@upgrade');
    Route::get('carrinho/menos/{rowId}/{qty}', 'CartController@downgrade');
    Route::post('carrinho', 'CartController@index')->name('carrinho.cupom');
    Route::post('carrinho/adicionar', 'CartController@store')->name('carrinho.adicionar');
    Route::post('carrinho/limpar', 'CartController@clear');
    Route::resource('favoritos', 'WishlistController');
    Route::post('favoritos/limpar', 'WishlistController@clear');
    Route::post('favoritos/adicionar', 'WishlistController@add');
    Route::get('favoritos/carrinho', 'WishListController@cart');
    Route::post('newsletter', 'NewsletterController@store');
    route::get("categoria", 'CategoryController@getCategoryFiltered');
    route::get("colecao", 'CollectionController@getCollectionFiltered');
    Route::get("colecoes", 'CollectionController@getCollectionList')->name('front.collection.list');
    Route::get("categoria/{slug}", 'CategoryController@getCategory')->name('front.category.slug');
    Route::get("categorias", 'CategoryController@getCategoryList')->name('front.category.list');
    Route::get("colecao/{slug}", 'CollectionController@getCollection')->name('front.collection.slug');
    Route::get("busca", 'ProductController@search')->name('front.get.product');
    Route::get("produto", 'ProductController@getProductGrade');
    Route::post("produto", 'ProductController@createReview')->name('product.review');
    Route::get("{product}", 'ProductController@show')->name('front.get.product');
    Route::get("promoção/{slug}", 'ProductPromotionController@getPromotionList');
});
