<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\Common\IntegrationEvent\IntegrationEventInterface;
use App\Application\Common\IntegrationEvent\User\UserActivatedIntegrationEvent;
use App\Application\Common\IntegrationEvent\User\UserCreatedIntegrationEvent;
use App\Application\Common\IntegrationEvent\User\UserRemovedIntegrationEvent;
use App\Application\Common\IntegrationEventMapperInterface;
use App\Domain\Common\DomainEventInterface;
use App\Domain\User\Event\UserActivatedEvent;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserRemovedEvent;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class IntegrationEventMapper implements IntegrationEventMapperInterface
{
    public function mapDomainEvent(DomainEventInterface $event): IntegrationEventInterface|null
    {
        return match (get_class($event)) {
            UserCreatedEvent::class => new UserCreatedIntegrationEvent($event->getUserId()),
            UserActivatedEvent::class => new UserActivatedIntegrationEvent($event->getUserId()),
            UserRemovedEvent::class => new UserRemovedIntegrationEvent($event->getUserId()),
            default => null,
        };
    }
}
