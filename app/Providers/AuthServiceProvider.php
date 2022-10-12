<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\Auth\CustomerGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Auth::extend('customer', function($app, $name, array $config) {
        //     return new CustomerGuard(Auth::createUserProvider($config['provider']));
        // });
        //

        //kita membuat gate dengan nama order-view, dimana dia meminta dua parameter yakni kustomer dan order
        Gate::define('order-view', function (Customer $customer, Order $order) {
        
            //kemudian dicek, jika customer id sama dengan customer_id yang ada pada table order
            //maka returnnya true
            //gate ini hanya akan mereturn true/false sebagai tanda diizinkan atau tidak
            return $customer->id == $order->customer_id;
        });
    }
}
