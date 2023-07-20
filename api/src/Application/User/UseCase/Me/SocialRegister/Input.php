<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\SocialRegister;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $provider,
        private string $providerResourceId,
        private ?string $nickname
    ) {}

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getProviderResourceId(): string
    {
        return $this->providerResourceId;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }
}
