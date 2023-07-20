<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\ChangePassword;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $userId,
        private ?string $currentPassword,
        private string $newPassword
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
