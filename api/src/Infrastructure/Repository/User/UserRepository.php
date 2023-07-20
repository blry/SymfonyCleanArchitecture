<?php

namespace App\Infrastructure\Repository\User;

use App\Domain\User\ValueObject\ProviderEnum;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Infrastructure\Repository\BaseRepository;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @extends BaseRepository<User>
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function isNicknameAvailable(string $nickname): bool
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.nickname = ?1')
            ->setParameter(1, $nickname);

        return !$qb->getQuery()->setMaxResults(1)->getResult();
    }

    public function isEmailAvailable(string $email, bool $includeUnconfirmed = false): bool
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->orWhere('u.email = ?1')
            ->setParameter(1, $email);

        if ($includeUnconfirmed) {
            $qb->orWhere('u.unconfirmedEmail = ?1');
        }

        return !$qb->getQuery()->setMaxResults(1)->getResult();
    }

    public function loadUserByUsername(string $id): ?UserInterface
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOneByProvider(string $provider, string $providerResourceId): ?User
    {
        ProviderEnum::from($provider);

        return $this->findOneBy([$provider => $providerResourceId]);
    }

    public function findOneByLogin(string $login): ?User
    {
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nickname';

        return $this->findOneBy([$field => $login]);
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->find($identifier);
    }
}
