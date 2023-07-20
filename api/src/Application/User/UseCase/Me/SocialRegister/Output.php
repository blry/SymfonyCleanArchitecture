<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\SocialRegister;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class Output
{
    public function __construct(
        private string $userId
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }
}
