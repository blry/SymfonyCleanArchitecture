<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\PatchGeneral;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $userId,
        private ?string $nickname,
        private ?string $locale,
        private ?bool $ads
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function getAds(): ?bool
    {
        return $this->ads;
    }
}
