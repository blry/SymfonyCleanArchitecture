<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\ChangeEmail;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $userId,
        private string $email,
        private string $password
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
