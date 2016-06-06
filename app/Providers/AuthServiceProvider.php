<?php

namespace App\Providers;

use App\User;
use App\Policies\UserPolicy;
use App\MacAddress;
use App\Policies\MacAddressPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            $auth = $request->header('X-Authorization');
            $user = null;
            if ($auth && strpos($auth, 'Bearer ') === 0) {
                $token = substr($auth, 7);
                $user = User::where('token', $token)->first();
            }
            return $user;
        });

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(MacAddress::class, MacAddressPolicy::class);
    }
}
