<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Common\DomainEventInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UserEmailUpdateCancelledEvent implements DomainEventInterface
{
    public function __construct(
        private string $userId,
        private string $cancelledEmail
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getCancelledEmail(): string
    {
        return $this->cancelledEmail;
    }
}
