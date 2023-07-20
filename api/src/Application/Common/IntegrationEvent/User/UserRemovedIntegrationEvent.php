<?php

declare(strict_types=1);

namespace App\Application\Common\IntegrationEvent\User;

use App\Application\Common\IntegrationEvent\IntegrationEventInterface;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class UserRemovedIntegrationEvent implements IntegrationEventInterface
{
    public function __construct(
        private string $userId
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }
}
