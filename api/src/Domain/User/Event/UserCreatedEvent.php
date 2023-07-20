<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Common\DomainEventInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UserCreatedEvent implements DomainEventInterface
{
    public function __construct(
        protected string $userId
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }
}
