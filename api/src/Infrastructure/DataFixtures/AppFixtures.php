<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Common\Service\IdGeneratorInterface;
use App\Domain\User\Service\PasswordHasherServiceInterface;
use App\Domain\User\User;
use App\Infrastructure\OAuth2\ScopeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class AppFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherServiceInterface $passwordHasher,
        private ClientManagerInterface $clientManager,
        private IdGeneratorInterface $idGenerator
    ) {}

    public function load(ObjectManager $manager)
    {
        $client = new Client('default', 'd92b93967a5b7082a42dd6ec6d798235', null);

        $scopes = [];
        foreach (ScopeEnum::AVAILABLE as $scope) {
            $scopes[] = new Scope($scope);
        }
        $client->setScopes(...$scopes);

        $client->setGrants(new Grant('password'), new Grant('social'));
        $this->clientManager->save($client);

        $user = new User($this->idGenerator->id(), 'AlexSterpu', 'ru');
        $user->setEmail('user@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'string'));
        $user->setPassword($this->passwordHasher->hashPassword($user, 'string'));

        $manager->persist($user);
        $manager->flush();

        //$user->confirmEmail();
        //$manager->flush();
    }
}
