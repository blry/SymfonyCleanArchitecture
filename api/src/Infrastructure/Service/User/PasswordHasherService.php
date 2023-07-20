<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\User;

use App\Domain\User\Service\PasswordHasherServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class PasswordHasherService extends UserPasswordHasher implements PasswordHasherServiceInterface
{
}
