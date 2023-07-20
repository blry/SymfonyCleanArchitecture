<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\Remove;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $userId
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }
}
