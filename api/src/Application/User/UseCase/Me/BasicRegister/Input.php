<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\BasicRegister;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $nickname,
        private string $password,
        private string $email,
        private string $locale,
        private bool   $ads
    ) {}

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getAds(): bool
    {
        return $this->ads;
    }
}
