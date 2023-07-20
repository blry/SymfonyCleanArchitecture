<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Common\Service\RepositoryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 *
 * @implements RepositoryInterface<User>
 */
interface UserRepositoryInterface extends RepositoryInterface, PasswordUpgraderInterface, UserLoaderInterface
{
    public function findOneByProvider(string $provider, string $providerResourceId): ?User;

    public function findOneByLogin(string $login): ?User;

    public function isNicknameAvailable(string $nickname): bool;

    public function isEmailAvailable(string $email, bool $includeUnconfirmed = false): bool;
}
