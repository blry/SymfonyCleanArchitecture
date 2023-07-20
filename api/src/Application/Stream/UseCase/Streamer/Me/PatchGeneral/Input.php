<?php

declare(strict_types=1);

namespace App\Application\Stream\UseCase\Streamer\Me\PatchGeneral;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $streamerId,
        private ?string $nickname,
    ) {}

    public function getStreamerId(): string
    {
        return $this->streamerId;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getBannedCountries(): ?array
    {
        return $this->bannedCountries;
    }

    public function getStreamingPlatforms(): ?array
    {
        return $this->streamingPlatforms;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function getTurnsMeOn(): ?string
    {
        return $this->turnsMeOn;
    }

    public function getTurnsMeOut(): ?string
    {
        return $this->turnsMeOut;
    }
}
