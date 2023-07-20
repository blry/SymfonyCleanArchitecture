<?php

declare(strict_types=1);

namespace App\Infrastructure\OAuth2;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Application\User\UseCase\Me\SocialRegister\Input as UserCreateInput;
use App\Application\User\UseCase\Me\SocialRegister\Output as UserCreateOutput;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class SocialUserProvider implements SocialUserProviderInterface
{
    use HandleTrait;

    public function __construct(
        private ClientRegistry $clientRegistry,
        private UserRepositoryInterface $userRepo,
        private MessageBusInterface $messageBus
    ) {}

    public function getUser(string $provider): ?User
    {
        $externalUser = $this->clientRegistry->getClient($provider)->fetchUser();

        $providerResourceId = $externalUser->getId();
        if (!$providerResourceId) {
            return null;
        }

        $user = $this->userRepo->findOneByProvider($provider, $providerResourceId);
        if ($user) {
            return $user;
        }

        if (!$_ENV['OAUTH2_SOCIAL_GRANT_USER_AUTO_CREATE']) {
            return null;
        }

        $nickname = method_exists($externalUser, 'getUsername') ? $externalUser->getUsername() : null;

        /** @var UserCreateOutput $result */
        $result = $this->handle(new UserCreateInput($provider, $providerResourceId, $nickname));

        return $this->userRepo->find($result->getUserId());
    }
}
