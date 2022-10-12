<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Ecommerce\CartController;
use Maatwebsite\Excel\Row;
use App\Http\Controllers\Ecommerce\FrontController;
use App\Http\Controllers\Ecommerce\LoginController;
use App\Http\Controllers\Ecommerce\OrderController;

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

Route::get('/', 'App\Http\Controllers\Ecommerce\FrontController@index')->name('front.index');
Route::get('/product', 'App\Http\Controllers\Ecommerce\FrontController@product')->name('front.product');
Route::get('/product/{slug}', 'App\Http\Controllers\Ecommerce\FrontController@show')->name('front.show_product');
Route::post('cart', 'App\Http\Controllers\Ecommerce\CartController@addToCart')->name('front.cart');
Route::get('/cart', 'App\Http\Controllers\Ecommerce\CartController@listCart')->name('front.list_cart');
Route::post('/cart/update', 'App\Http\Controllers\Ecommerce\CartController@updateCart')->name('front.update_cart');

Route::get('/checkout', 'App\Http\Controllers\Ecommerce\CartController@checkout')->name('front.checkout');
Route::post('/checkout', 'App\Http\Controllers\Ecommerce\CartController@processCheckout')->name('front.store_checkout');
Route::get('/checkout/{invoice}', 'App\Http\Controllers\Ecommerce\CartController@checkoutFinish')->name('front.finish_checkout');

Route::get('/product/ref/{user}/{product}', [FrontController::class, 'referalProduct'])->name('front.afiliasi');


Route::group(['prefix' => 'member', 'namespace' => 'Ecommerce'], function(){
    Route::get('login', [LoginController::class, 'loginForm'])->name('customer.login');
    Route::post('login', [LoginController::class, 'login'])->name('customer.post_login');
    Route::get('verify/{token}', [FrontController::class, 'verifyCustomerRegistration'])->name('customer.verify');
    Route::group(['middleware' => 'customer'], function(){
        Route::get('dashboard', [LoginController::class, 'dashboard'])->name('customer.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('customer.logout');
        Route::get('orders', [OrderController::class, 'index'])->name('customer.orders');
        Route::get('orders/{invoice}', [OrderController::class, 'view'])->name('customer.view_order');
        Route::get('orders/pdf/{invoice}', [OrderController::class, 'pdf'])->name('customer.order_pdf');
        Route::get('payment', [OrderController::class, 'paymentForm'])->name('customer.paymentForm');
        Route::post('payment', [OrderController::class, 'storePayment'])->name('customer.savePayment');
        Route::get('setting', [FrontController::class, 'customerSettingForm'])->name('customer.settingForm');
        Route::post('setting', [FrontController::class, 'customerUpdateProfile'])->name('customer.setting');
        Route::post('orders/accept', [OrderController::class, 'acceptOrder'])->name('customer.order_accept');
        Route::get('orders/return/{invoice}', [OrderController::class, 'returnForm'])->name('customer.order_return');
        Route::put('orders/return/{invoice}', [OrderController::class, 'processReturn'])->name('customer.return');
        Route::get('/afiliasi', [FrontController::class, 'listCommission'])->name('customer.affiliate');

    });
    });

Auth::routes();

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('category', CategoryController::class)->except(['create', 'show']);

    // Route::resource('product', ProductController::class);

    Route::resource('product', ProductController::class)->except(['show']);
    Route::get('/product/bulk', 'App\Http\Controllers\ProductController@massUploadForm')->name('product.bulk');
    Route::post('/product/bulk', 'App\Http\Controllers\ProductController@massUpload')->name('product.saveBulk');
    Route::group(['prefix' => 'orders'], function() {
        Route::get('/', 'App\Http\Controllers\OrderController@index')->name('orders.index');
        Route::delete('/{id}', 'App\Http\Controllers\OrderController@destroy')->name('orders.destroy');
        Route::get('/{invoice}', 'App\Http\Controllers\OrderController@view')->name('orders.view');
        Route::get('/payment/{invoice}', 'App\Http\Controllers\OrderController@acceptPayment')->name('orders.approve_payment');
        Route::post('/shipping', 'App\Http\Controllers\OrderController@shippingOrder')->name('orders.shipping');
        Route::get('/return/{invoice}', 'App\Http\Controllers\OrderController@return')->name('orders.return');
        Route::post('/return', 'App\Http\Controllers\OrderController@approveReturn')->name('orders.approve_return');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/order', [HomeController::class, 'orderReport'])->name('report.order');
        Route::get('/order/pdf/{daterange}', [HomeController::class, 'orderReportPdf'])->name('report.order_pdf');
        Route::get('/return', 'App\Http\Controllers\HomeController@returnReport')->name('report.return');
        Route::get('/return/pdf/{daterange}', 'App\Http\Controllers\HomeController@returnReportPdf')->name('report.return_pdf');
    });
});
