<?php

namespace App\Providers;

use App\Http\Grant\SocialGrant;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class SocialPassportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app(AuthorizationServer::class)->enableGrantType($this->makeRequestGrant(), Passport::tokensExpireIn());
    }
    
    public function register()
    {
    }


    protected function makeRequestGrant()
    {
        $grant = new SocialGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        return $grant;
    }
}
