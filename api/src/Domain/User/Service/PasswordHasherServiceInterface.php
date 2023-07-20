<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
interface PasswordHasherServiceInterface
{
    public function hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword): string;
    public function isPasswordValid(PasswordAuthenticatedUserInterface $user, string $raw): bool;
    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool;
}
