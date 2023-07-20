<?php

declare(strict_types=1);

namespace App\Application\User\UseCase\Me\ConfirmEmail;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
readonly class Input
{
    public function __construct(
        private string $activationCode
    ) {}

    public function getActivationCode(): string
    {
        return $this->activationCode;
    }
}
