<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\Bundle\OAuth2ServerBundle\AuthorizationServer\GrantTypeInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Domain\User\User;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class SocialGrant extends AbstractGrant implements GrantTypeInterface
{
    public const NAME = 'social';

    /**
     * @throws \Exception
     */
    public function __construct(
        private readonly ClientRegistry              $clientRegistry,
        private readonly SocialUserProviderInterface $userProvider,
        RefreshTokenRepositoryInterface              $refreshTokenRepository
    ) {
        $this->setRefreshTokenTTL(new \DateInterval($_ENV['OAUTH2_REFRESH_TOKEN_TTL']));
        $this->setRefreshTokenRepository($refreshTokenRepository);
    }

    /**
     * @throws OAuthServerException
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function respondToAccessTokenRequest(ServerRequestInterface $request, ResponseTypeInterface $responseType, \DateInterval $accessTokenTTL): ResponseTypeInterface
    {
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getUserIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getUserIdentifier(), $finalizedScopes);
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));
        $responseType->setAccessToken($accessToken);

        $refreshToken = $this->issueRefreshToken($accessToken);
        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));
            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;
    }

    /**
     * @throws OAuthServerException
     */
    protected function validateUser(ServerRequestInterface $request): User
    {
        $provider = $this->getRequestParameter('provider', $request);
        if (is_null($provider)) {
            throw OAuthServerException::invalidRequest('provider');
        }
        if (!$this->isProviderSupported($provider)) {
            throw OAuthServerException::invalidRequest(
                'provider',
                sprintf(
                    'Invalid provider. Available providers: %s.',
                    implode(',', $this->clientRegistry->getEnabledClientKeys())
                )
            );
        }

        $code = $this->getRequestParameter('code', $request);
        if (is_null($code)) {
            throw OAuthServerException::invalidRequest('code');
        }

        $user = $this->userProvider->getUser($provider);
        if ($user instanceof User === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    protected function isProviderSupported($provider): bool
    {
        return in_array($provider, $this->clientRegistry->getEnabledClientKeys(), true);
    }

    public function getIdentifier(): string
    {
        return self::NAME;
    }

    /**
     * @throws \Exception
     */
    public function getAccessTokenTTL(): ?\DateInterval
    {
        return new \DateInterval($_ENV['OAUTH2_ACCESS_TOKEN_TTL']);
    }
}
