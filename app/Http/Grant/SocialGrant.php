<?php
/**
 * Created by IntelliJ IDEA.
 * User: pnLinh
 * Date: 3/19/2018
 * Time: 9:45 PM
 */

namespace App\Http\Grant;


use App\SocialAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\User;
use RuntimeException;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class SocialGrant extends AbstractGrant
{

    /**
     * Return the grant identifier that can be used in matching up requests.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return 'social';
    }


    public function __construct(
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository
    )
    {
        $this->setUserRepository($userRepository);
        $this->setRefreshTokenRepository($refreshTokenRepository);

        $this->refreshTokenTTL = new \DateInterval('P1M');
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    )
    {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $provider = $this->getRequestParameter('provider', $request);
        if (is_null($provider)) {
            throw OAuthServerException::invalidRequest('provider');
        }

        $social_token = $this->getRequestParameter('social_token', $request);
        if (is_null($social_token)) {
            throw OAuthServerException::invalidRequest('social_token');
        }

        $socialUser = Socialite::driver($provider)->userFromToken($social_token);

//        try {
//
//        } catch (\Exception $ex) {
//            $test = $ex;
//        }


        $user = $this->getSocialUser($socialUser, $provider);

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    protected function getSocialUser(\Laravel\Socialite\Two\User $socialUser, $socialProvider)
    {

        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.' . $provider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }
        $socialAccount = SocialAccount::where('provider_user_id', $socialUser->id)
            ->where('provider', $socialProvider)->first();

        if (!$socialAccount) {
            try {
                DB::beginTransaction();
                $user = new \App\User();
                $user->name = $socialUser->name;
                $user->email = $socialUser->email;
                $user->password = Hash::make(str_random(10));
                $user->status = 1;
                $user->save();

                $socialAccount = new SocialAccount();
                $socialAccount->provider = $socialProvider;
                $socialAccount->provider_user_id = $socialUser->id;

                $user->socialAccounts()->save($socialAccount);
                DB::commit();
                return new User($user->id);

            } catch (\Exception $ex) {
                DB::rollBack();
                return;
            }
        } else
            return new User($socialAccount->user_id);
    }

}