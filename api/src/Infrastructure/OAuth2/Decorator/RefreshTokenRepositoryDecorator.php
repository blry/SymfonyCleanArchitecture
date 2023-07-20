<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2\Decorator;

use App\Infrastructure\OAuth2\ScopeEnum;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use League\Bundle\OAuth2ServerBundle\Repository\RefreshTokenRepository as LeagueRefreshTokenRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class RefreshTokenRepositoryDecorator implements RefreshTokenRepositoryInterface
{
    public function __construct(
        private LeagueRefreshTokenRepository $decorated,
        private RequestStack $requestStack
    ) {}

    public function getNewRefreshToken(): ?RefreshTokenEntityInterface
    {
        $withRefreshToken = in_array(
            ScopeEnum::OFFLINE_ACCESS,
            $this->requestStack->getCurrentRequest()->attributes->all('scopesResolved'),
            true
        );

        return $withRefreshToken ? $this->decorated->getNewRefreshToken() : null;
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $this->decorated->persistNewRefreshToken($refreshTokenEntity);
    }

    public function revokeRefreshToken($tokenId): void
    {
        $this->decorated->revokeRefreshToken($tokenId);
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        return $this->decorated->isRefreshTokenRevoked($tokenId);
    }
}
