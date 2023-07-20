<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber\User;

use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use League\OAuth2\Server\Exception\OAuthServerException;
use App\Domain\User\Service\PasswordHasherServiceInterface;
use App\Domain\User\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use League\Bundle\OAuth2ServerBundle\Event\ScopeResolveEvent;
use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class OAuth2EventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepo,
        private readonly PasswordHasherServiceInterface $passwordHasher,
        private readonly RequestStack                   $requestStack
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::USER_RESOLVE => 'onUserResolve',
            OAuth2Events::SCOPE_RESOLVE => 'onScopeResolve',
//            RequestEvent::USER_AUTHENTICATION_FAILED => '',
//            RequestEvent::CLIENT_AUTHENTICATION_FAILED => '',
//            RequestEvent::REFRESH_TOKEN_CLIENT_FAILED => '',
        ];
    }

    /**
     * Sets scopesResolved request attribute. The method is not called when grant_type is refresh_token.
     */
    public function onScopeResolve(ScopeResolveEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $hasScopes = $request?->request?->all()['scope'] ?? false;
        if ($hasScopes) {
            $scopes = array_map(static function (Scope $scope) { return (string) $scope; }, $event->getScopes());
            $request?->attributes->set('scopesResolved', $scopes);
        } else {
            // If no scopes asked - do not set any
            $event->setScopes();
            $request?->attributes->set('scopesResolved', []);
        }
    }

    /**
     * Called when is grant_type=password
     *
     * @throws OAuthServerException
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        $user = $this->userRepo->findOneByLogin($event->getUsername());
        if (!$user || !$user->getPassword() || !$this->passwordHasher->isPasswordValid($user, $event->getPassword())) {
            throw OAuthServerException::invalidCredentials();
        }

        $event->setUser($user);
    }
}
